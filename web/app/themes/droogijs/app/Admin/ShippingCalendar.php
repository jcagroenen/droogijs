<?php

namespace App\Admin;

/**
 * Shipping Calendar Admin Page
 *
 * Provides a settings page for configuring delivery date options.
 */
class ShippingCalendar
{
    /**
     * Option name for storing settings.
     */
    public const OPTION_NAME = 'shipping_calendar_settings';

    /**
     * Bootstrap the admin page.
     */
    public function boot(): void
    {
        add_action('admin_menu', [$this, 'addMenuPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    /**
     * Add the menu page.
     */
    public function addMenuPage(): void
    {
        add_menu_page(
            __('Verzendkalender', 'flavor'),
            __('Verzendkalender', 'flavor'),
            'manage_woocommerce',
            'shipping-calendar',
            [$this, 'renderPage'],
            'dashicons-calendar-alt',
            56 // After WooCommerce
        );
    }

    /**
     * Register settings.
     */
    public function registerSettings(): void
    {
        register_setting(
            'shipping_calendar',
            self::OPTION_NAME,
            [
                'type' => 'array',
                'sanitize_callback' => [$this, 'sanitizeSettings'],
                'default' => $this->getDefaults(),
            ]
        );
    }

    /**
     * Get default settings.
     */
    public function getDefaults(): array
    {
        return [
            'enabled' => true,
            'min_days' => 1,
            'disabled_weekdays' => [0], // Sunday
            'blocked_dates' => '',
            'helper_text' => 'Levering is mogelijk vanaf morgen (behalve op zondag)',
        ];
    }

    /**
     * Check if the delivery date picker is enabled.
     */
    public static function isEnabled(): bool
    {
        $settings = self::getSettings();

        // Disabled via toggle
        if (empty($settings['enabled'])) {
            return false;
        }

        // Disabled if all 7 weekdays are blocked
        if (count($settings['disabled_weekdays']) >= 7) {
            return false;
        }

        return true;
    }

    /**
     * Get current settings.
     */
    public static function getSettings(): array
    {
        $instance = new self();
        $settings = get_option(self::OPTION_NAME, []);
        return wp_parse_args($settings, $instance->getDefaults());
    }

    /**
     * Sanitize settings on save.
     */
    public function sanitizeSettings(array $input): array
    {
        return [
            'enabled' => ! empty($input['enabled']),
            'min_days' => absint($input['min_days'] ?? 1),
            'disabled_weekdays' => array_map('absint', $input['disabled_weekdays'] ?? []),
            'blocked_dates' => sanitize_textarea_field($input['blocked_dates'] ?? ''),
            'helper_text' => sanitize_textarea_field($input['helper_text'] ?? ''),
        ];
    }

    /**
     * Render the settings page.
     */
    public function renderPage(): void
    {
        $settings = self::getSettings();
        $weekdays = [
            0 => 'Zondag',
            1 => 'Maandag',
            2 => 'Dinsdag',
            3 => 'Woensdag',
            4 => 'Donderdag',
            5 => 'Vrijdag',
            6 => 'Zaterdag',
        ];
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

            <form method="post" action="options.php">
                <?php settings_fields('shipping_calendar'); ?>

                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php _e('Datumkiezer', 'flavor'); ?></th>
                        <td>
                            <label>
                                <input
                                    type="checkbox"
                                    name="<?php echo self::OPTION_NAME; ?>[enabled]"
                                    value="1"
                                    <?php checked($settings['enabled']); ?>
                                />
                                <?php _e('Datumkiezer inschakelen', 'flavor'); ?>
                            </label>
                            <p class="description">
                                <?php _e('Schakel uit om de leverdatum keuze te verbergen op de productpagina.', 'flavor'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="min_days"><?php _e('Minimum dagen vooruit', 'flavor'); ?></label>
                        </th>
                        <td>
                            <input
                                type="number"
                                id="min_days"
                                name="<?php echo self::OPTION_NAME; ?>[min_days]"
                                value="<?php echo esc_attr($settings['min_days']); ?>"
                                min="0"
                                max="30"
                                class="small-text"
                            />
                            <p class="description">
                                <?php _e('Hoeveel dagen van tevoren moet een klant minimaal bestellen?', 'flavor'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><?php _e('Gesloten dagen', 'flavor'); ?></th>
                        <td>
                            <fieldset>
                                <?php foreach ($weekdays as $num => $label) : ?>
                                    <label style="display: block; margin-bottom: 5px;">
                                        <input
                                            type="checkbox"
                                            name="<?php echo self::OPTION_NAME; ?>[disabled_weekdays][]"
                                            value="<?php echo esc_attr($num); ?>"
                                            <?php checked(in_array($num, $settings['disabled_weekdays'])); ?>
                                        />
                                        <?php echo esc_html($label); ?>
                                    </label>
                                <?php endforeach; ?>
                            </fieldset>
                            <p class="description">
                                <?php _e('Op welke dagen is er geen levering mogelijk?', 'flavor'); ?>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="blocked_dates"><?php _e('Geblokkeerde datums', 'flavor'); ?></label>
                        </th>
                        <td>
                            <textarea
                                id="blocked_dates"
                                name="<?php echo self::OPTION_NAME; ?>[blocked_dates]"
                                rows="6"
                                cols="50"
                                class="large-text code"
                            ><?php echo esc_textarea($settings['blocked_dates']); ?></textarea>
                            <p class="description">
                                <?php _e('Eén datum per regel in formaat DD-MM-YYYY. Voor een periode: DD-MM-YYYY - DD-MM-YYYY', 'flavor'); ?><br>
                                <?php _e('Voorbeeld:', 'flavor'); ?><br>
                                <code>25-12-<?php echo date('Y'); ?></code><br>
                                <code>31-12-<?php echo date('Y'); ?> - 01-01-<?php echo date('Y') + 1; ?></code>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="helper_text"><?php _e('Helptekst', 'flavor'); ?></label>
                        </th>
                        <td>
                            <textarea
                                id="helper_text"
                                name="<?php echo self::OPTION_NAME; ?>[helper_text]"
                                rows="2"
                                cols="50"
                                class="large-text"
                            ><?php echo esc_textarea($settings['helper_text']); ?></textarea>
                            <p class="description">
                                <?php _e('Tekst die onder de datumkiezer wordt getoond op de productpagina.', 'flavor'); ?>
                            </p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(__('Opslaan', 'flavor')); ?>
            </form>
        </div>
        <?php
    }
}
