<?php
use backend\bus\RoleBUS;
use backend\bus\UserBUS;
use backend\enums\StatusEnums;
use backend\models\UserModel;

if (!defined('_CODE')) {
    die('Access denied');
}

if (isPost()) {

    $filterAll = filter();
    if (isset($_POST['registerBtn'])) {
        error_log('Register button clicked!');
        //Check email and phone number already taken:
        $emailCheck = UserBUS::getInstance()->getModelByEmail($filterAll['email']);
        $phoneCheck = UserBUS::getInstance()->getModelByPhone($filterAll['phone']);
        if ($emailCheck) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Email already exists!']);
            exit;
        }

        if ($phoneCheck) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Phone number already exists!']);
            exit;
        }

        $userModel = new UserModel(
            null,
            $filterAll['username'],
            $filterAll['password'],
            $filterAll['email'],
            $filterAll['fullname'],
            $filterAll['phone'],
            $filterAll['gender'],
            null,
            RoleBUS::getInstance()->getModelById(4)->getId(),
            StatusEnums::INACTIVE,
            $filterAll['address'],
            null,
            null,
            null,
            null
        );
        //TODO: Fix the validate model section at backend: there may be errors
        $errors = UserBUS::getInstance()->validateModel($userModel);
        error_log('Errors: ' . json_encode($errors));
        if ($filterAll['password_confirm'] == null || trim($filterAll['password_confirm']) == "") {
            $errors['password_confirm']['required'] = 'Password confirm is required!';
        }
        if (!($filterAll['password_confirm'] == $filterAll['password'])) {
            $errors['password_confirm']['comfirm'] = 'Confirm password does not match!';
        }
        if (count($errors) <= 0) {
            $activeToken = sha1(uniqid() . time());
            $userModel->setPassword(password_hash($filterAll['password'], PASSWORD_DEFAULT));
            $userModel->setCreateAt(date("Y-m-d H:i:s"));
            $userModel->setActiveToken($activeToken);
            $insertStatus = UserBUS::getInstance()->addModel($userModel);
            if ($insertStatus) {
                $_SESSION['registerUserId'] = $insertStatus;
                // Tạo link kích hoạt
                $linkActive = _WEB_HOST . '?module=auth&action=active&token=' . $activeToken;
                // Thiết lập gửi mail
                $subject = $filterAll['fullname'] . ' please active your account!!!';
                $content = 'Chào ' . $filterAll['fullname'] . '<br/>';
                $content .= 'Please click on this link below here to activate your account:' . '<br/>';
                $content .= $linkActive . '<br/>';
                $content .= 'Thank you so much!!';

                // Tiến hành gửi mail
                $sendMailStatus = sendMail($filterAll['email'], $subject, $content);

                if ($sendMailStatus) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'success', 'message' => 'Successfully registered, please check your email to activate your account!']);
                    exit;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'error', 'message' => 'The system is having problems, please try again later!']);
                    exit;
                }
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'The system is having problems, please try again later!']);
                exit;
            }
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Please check the data', 'errors' => $errors]);
            exit;
        }
    }
}

$data = [
    'pageTitle' => 'Register'
];
?>

<div id="header">
    <?php layouts('header', $data); ?>
</div>

<body>
    <div class="row">
        <div class="col-4" style="margin: 24px auto;">
            <form class="cw" action="" method="post">
                <h2 class="header_register" style="text-align:center; text-transform: uppercase;">Register</h2>
                <div class="form-group mg-form mt-3">
                    <label for="">Full name</label>
                    <input name="fullname" class="form-control" type="text" placeholder="Your full name...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Username</label>
                    <input name="username" class="form-control" type="text" placeholder="Your username...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Email</label>
                    <input name="email" class="form-control" type="text" placeholder="Your email...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Phone number</label>
                    <input name="phone" class="form-control" type="number" placeholder="Your phone number...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Address</label>
                    <input name="address" class="form-control" type="text" placeholder="Your address...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Password</label>
                    <input name="password" class="form-control" type="password" placeholder="Your password...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Re-type password</label>
                    <input name="password_confirm" class="form-control" type="password"
                        placeholder="Re-type your password...">
                </div>

                <div class="form-group mg-form mt-3">
                    <label for="">Gender</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="male">
                        <label class="form-check-label" for="gender-male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" value="female">
                        <label class="form-check-label" for="gender-female">Female</label>
                    </div>
                </div>

                <button id="registerBtn" name="registerBtn" type="button" class="btn btn-primary btn-block mg-form"
                    style="width:100%; margin-top:16px;">Register</button>
                <hr>
                <p class="text-center"><a href="?module=auth&action=login">Already have an account? Log
                        in here</a></p>
            </form>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="<?php echo _WEB_HOST_TEMPLATE ?>/js/register_handling.js"></script>
</body>