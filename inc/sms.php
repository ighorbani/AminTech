<?php
function create_sms_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'sms_messages';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        receptor varchar(20) NOT NULL,
        message text NOT NULL,
        date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'create_sms_table');

// ارسال کد تایید به شماره همراه
function send_verification_code()
{
    $phone = $_POST['phone'];
    $code = rand(1000, 9999); // تولید کد ۴ رقمی

    // ذخیره کد تایید در سرور (مثلاً در دیتابیس یا کش)
    save_code_in_database($phone, $code);

    // ارسال کد از طریق API کاوه نگار
    $api_key = 'YOUR_API_KEY';
    $message = 'کد تایید شما: ' . $code;
    $url = "https://api.kavenegar.com/v1/$api_key/sms/send.json?receptor=$phone&message=" . urlencode($message);

    $response = file_get_contents($url);

    // ذخیره پیامک در دیتابیس
    global $wpdb;
    $table_name = $wpdb->prefix . 'sms_messages';
    $wpdb->insert(
        $table_name,
        array(
            'receptor' => $phone,
            'message' => $message,
            'date' => current_time('mysql')
        )
    );
    echo json_encode(['status' => 'success']);
}


// بررسی کد تایید
function verify_code()
{
    $entered_code = $_POST['code'];
    $phone = $_SESSION['phone']; // شماره موبایل کاربر از جلسه

    // دریافت کد ذخیره شده از ترنزینت
    $stored_code = get_code_from_database($phone);

    if ($entered_code == $stored_code) {
        // بررسی اینکه آیا کاربری با این شماره موبایل وجود دارد
        $user = get_user_by('meta_value', $phone); // جستجوی کاربر با متا 'phone'

        if ($user) {
            // اگر کاربر وجود داشت، لاگین کنید
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);
            echo json_encode(['status' => 'success', 'message' => 'لاگین موفق']);
        } else {
            // اگر کاربری وجود نداشت، یک کاربر جدید بسازید
            $user_id = wp_create_user($phone, wp_generate_password(), $phone . '@example.com');

            if (is_wp_error($user_id)) {
                echo json_encode(['status' => 'error', 'message' => 'خطا در ساخت کاربر جدید']);
            } else {
                // ذخیره شماره موبایل به‌عنوان متای کاربر
                update_user_meta($user_id, 'phone', $phone);

                // لاگین کاربر جدید
                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);
                echo json_encode(['status' => 'success', 'message' => 'کاربر جدید ساخته و لاگین شد']);
            }

            wp_safe_redirect('/my-account/');
            exit;
        }
    } else {
        // کد تایید اشتباه است
        echo json_encode(['status' => 'error', 'message' => 'کد تایید اشتباه است']);
    }
}

function save_code_in_database($phone, $code)
{
    // ساخت یک کلید منحصر به فرد برای ذخیره ترنزینت
    $transient_key = 'verification_code_' . $phone;

    // ذخیره کد به عنوان ترنزینت برای 10 دقیقه (600 ثانیه)
    set_transient($transient_key, $code, 600); // 600 ثانیه یعنی 10 دقیقه

    return true;
}

function get_code_from_database($phone)
{
    // کلید ترنزینت باید همان باشد که هنگام ذخیره استفاده شد
    $transient_key = 'verification_code_' . $phone;

    // دریافت کد تایید از ترنزینت
    $stored_code = get_transient($transient_key);

    return $stored_code;
}

// افزودن فیلد شماره موبایل به صفحه ویرایش پروفایل
function add_phone_field_to_user_profile($user)
{
    ?>
    <h3>اطلاعات تماس</h3>
    <table class="form-table">
        <tr>
            <th><label for="phone">شماره موبایل</label></th>
            <td>
                <input type="text" name="phone" id="phone"
                    value="<?php echo esc_attr(get_the_author_meta('phone', $user->ID)); ?>" class="regular-text" /><br />
                <span class="description">لطفا شماره موبایل کاربر را وارد کنید.</span>
            </td>
        </tr>
    </table>
    <?php
}
// add_action('show_user_profile', 'add_phone_field_to_user_profile');  // برای صفحه پروفایل خود کاربر
add_action('edit_user_profile', 'add_phone_field_to_user_profile');  // برای صفحه ویرایش کاربران توسط ادمین

// ذخیره شماره موبایل کاربر هنگام ذخیره پروفایل
function save_phone_field_in_user_profile($user_id)
{
    // بررسی اینکه آیا کاربر اجازه ذخیره این اطلاعات را دارد
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // ذخیره شماره موبایل در متای کاربر
    update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
}
// add_action('personal_options_update', 'save_phone_field_in_user_profile'); // برای ذخیره اطلاعات پروفایل خود کاربر
add_action('edit_user_profile_update', 'save_phone_field_in_user_profile'); // برای ذخیره اطلاعات کاربران توسط ادمین


// افزودن منوی جدید به بخش مدیریت
function add_sms_menu_to_admin()
{
    add_menu_page(
        'پیامک‌ها',            // عنوان صفحه
        'پیامک‌ها',            // نام منو
        'manage_options',       // سطح دسترسی
        'sms_messages',         // slug یکتای منو
        'display_sms_messages', // تابعی که صفحه را نمایش می‌دهد
        'data:image/svg+xml;base64,' . base64_encode('
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M19.8681 4.13206C17.9421 2.20606 15.3701 1.06606 12.5961 0.914062H11.9751C10.2771 0.914062 8.56706 1.31906 7.04606 2.09206C5.20906 3.00406 3.66306 4.42306 2.57406 6.17206C1.49706 7.92006 0.914062 9.93506 0.914062 12.0001C0.914062 13.4191 1.18006 14.8001 1.71206 16.1301C1.86406 16.5101 1.91506 16.8651 1.85206 17.2451L1.58606 19.8421C1.51006 20.5511 1.75106 21.2481 2.25706 21.7421C2.75106 22.2491 3.44806 22.5021 4.15706 22.4261L6.81806 22.1351C7.13506 22.0841 7.50206 22.1351 7.87006 22.2871C9.18806 22.8191 10.5691 23.0851 11.9751 23.0851H12.0001C14.0651 23.0851 16.0801 22.5151 17.8281 21.4251C19.5891 20.3481 20.9951 18.7901 21.9201 16.9531C22.6931 15.4331 23.0991 13.7091 23.0861 11.9991V11.3531C22.9341 8.62906 21.7941 6.07106 19.8681 4.13206ZM12.9631 15.4841H9.16206C8.64206 15.4841 8.21206 15.0531 8.21206 14.5341C8.21206 14.0021 8.64306 13.5841 9.16206 13.5841H12.9631C13.4951 13.5841 13.9131 14.0021 13.9131 14.5341C13.9131 15.0541 13.4951 15.4841 12.9631 15.4841ZM14.8631 10.4161H9.16206C8.63006 10.4161 8.21206 9.98506 8.21206 9.46606C8.21206 8.93406 8.63006 8.51606 9.16206 8.51606H14.8631C15.3821 8.51606 15.8131 8.93406 15.8131 9.46606C15.8131 9.98606 15.3821 10.4161 14.8631 10.4161Z" fill="black"/>
            </svg>
        '),
        25                    // موقعیت منو در نوار ادمین
    );
}
add_action('admin_menu', 'add_sms_menu_to_admin');


// نمایش پیامک‌های ارسال شده در جدول
function display_sms_messages()
{
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">پیامک‌های ارسال شده</h1>
        <form method="post">
            <?php
            // نمونه‌ای از جدول پیامک‌ها
            $sms_table = new SMS_List_Table();
            $sms_table->prepare_items();
            $sms_table->display();
            ?>
        </form>
    </div>
    <?php
}

// تعریف کلاس جدول پیامک‌ها
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class SMS_List_Table extends WP_List_Table
{
    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array('date' => array('date', false));
        $this->_column_headers = array($columns, $hidden, $sortable);

        // گرفتن داده‌های پیامک از دیتابیس (اینجا باید پیامک‌ها را از جدول یا متای دیتابیس بخوانید)
        global $wpdb;
        $table_name = $wpdb->prefix . 'sms_messages'; // باید جدول پیامک‌ها باشد
        $data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY date DESC", ARRAY_A);

        $this->items = $data;
    }

    function get_columns()
    {
        $columns = array(
            'receptor' => 'شماره گیرنده',
            'message' => 'متن پیام',
            'date' => 'تاریخ ارسال',
        );
        return $columns;
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'receptor':
            case 'message':
            case 'date':
                return $item[$column_name];
            default:
                return print_r($item, true);
        }
    }
}