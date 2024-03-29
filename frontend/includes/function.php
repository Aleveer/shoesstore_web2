<!-- Các hàm xử lí chung của project -->
<?php
if (!defined('_CODE')) {
    die('Access denied');
}

// Thư viện PHP Mailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Hàm giúp nhúng header và footer nhanh hơn vào các file
function layouts($layoutName = 'header', $data = [])
{
    if (file_exists(_WEB_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php')) {
        require_once _WEB_PATH_TEMPLATE . '/layouts/' . $layoutName . '.php';
    }
}

// Hàm gửi mail 
function sendMail($to, $subject, $content)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->CharSet = "UTF-8";
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'manshpypro@gmail.com';                     //SMTP username
        $mail->Password   = 'ztbwjqcpunymawdz';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('manshpypro@gmail.com', 'ManDuong');
        $mail->addAddress($to);     //Add a recipient

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;


        $sendMailStatus = $mail->send();
        // echo 'Gửi thành công!';
    } catch (Exception $e) {
        echo "Gửi mail thất bại. Mailer Error: {$mail->ErrorInfo}";
    }

    return $sendMailStatus;
}



// Kiểm tra phương thức GET
function isGet()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') return true;
    return false;
}

// Kiểm tra phương thức POST
function isPost()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') return true;
    return false;
}

// Hàm filter lọc dữ liệu
function filter()
{
    $filterArr = [];
    if (isGet()) {
        // Xử lí dữ liệu trước khi hiển thị ra
        if (!empty($_GET)) {
            foreach ($_GET as $key => $val) {
                $key = strip_tags($key);
                if (is_array($val)) {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }

    if (isPost()) {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                if (is_array($val)) {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY);
                } else {
                    $filterArr[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }
    }
    return $filterArr;
}

// Kiểm tra email
function isEmail($email)
{
    $checkMail = filter_var($email, FILTER_VALIDATE_EMAIL);
    return $checkMail;
}

// Kiểm tra số nguyên
function isNumberInt($number)
{
    $checkNumberInt = filter_var($number, FILTER_VALIDATE_INT);
    return $checkNumberInt;
}



// Kiểm tra số thực
function isNumberFloat($number)
{
    $checkNumberFloat = filter_var($number, FILTER_VALIDATE_FLOAT);
    return $checkNumberFloat;
}

// Kiểm tra sđt
function isPhone($phone)
{
    $pattern = '/^(?:\+?84|0)(?:\d){9,10}$/';
    return preg_match($pattern, $phone);
}

// Hàm in ra message
function getMsg($msg, $type = 'sucess')
{
    echo "<div class='text-center alert alert-$type'>";
    echo $msg;
    echo '</div>';
}


// Hàm chuyển hướng
function redirect($path = 'index.php')
{
    header("location: $path");
    exit;
}


// Hàm thông báo lỗi
function formError($name, $errors)
{
    return !empty($errors[$name]) ? '<p style="color:red;">' . reset($errors[$name]) . '</p>' : '';
}

// Hàm hiển thị dữ liệu cũ
function formOldInfor($name, $duLieuDaNhap, $default = '')
{
    return (!empty($duLieuDaNhap[$name])) ? $duLieuDaNhap[$name] : $default;
}

function isLogin()
{
    return isset($_SESSION['userId']);
}

function requireLogin()
{
    if (!isLogin()) {
        redirect('login.php');
    }
}
