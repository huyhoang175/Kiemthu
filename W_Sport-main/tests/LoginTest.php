<?php
use PHPUnit\Framework\TestCase;
use \PHPUnit\Framework\MockObject\MockObject;

class LoginTest extends TestCase {
    private $connect;

    protected function setUp(): void {
        // Giả lập kết nối cơ sở dữ liệu
        $this->connect = $this->createMock(mysqli::class);

        // Tạo một giả lập cho câu lệnh
        $stmt = $this->createMock(mysqli_stmt::class);
        $result = $this->createMock(mysqli_result::class);

        // Thiết lập kết quả giả lập
        $result->method('num_rows')->willReturn(1);
        $result->method('fetch_assoc')->willReturn([
            '_id' => 1,
            'username' => 'testuser',
            'password' => 'testpass'
        ]);

        // Thiết lập câu lệnh giả lập
        $stmt->method('execute')->willReturn(true);
        $stmt->method('get_result')->willReturn($result);
        $stmt->method('bind_param')->willReturn(true);

        // Thiết lập kết nối giả lập
        $this->connect->method('prepare')->willReturn($stmt);
    }

    public function testLoginSuccess() {
        $_POST['username'] = 'nguyenhuy2';
        $_POST['password'] = '123456';

        ob_start();
        include '/xampp/htdocs/W_Sport-main/PHP/Login.php';
        ob_end_clean();

        $this->assertEquals(1, $_SESSION['user_id']);
        
        $headers = headers_list();
        $found = false;
        foreach ($headers as $header) {
            if (strpos($header, 'Location: Index.php') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Không tìm thấy chuyển hướng đến Index.php trong các header');
    }

    public function testLoginWrongPassword() {
        $_POST['username'] = 'nguyenhuy2';
        $_POST['password'] = '22222222';

        ob_start();
        include '/xampp/htdocs/W_Sport-main/PHP/Login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Sai mật khẩu', $output);
    }

    public function testLoginUserNotFound() {
        $stmt = $this->createMock(mysqli_stmt::class);
        $result = $this->createMock(mysqli_result::class);

        $result->method('num_rows')->willReturn(0);

        $stmt->method('execute')->willReturn(true);
        $stmt->method('get_result')->willReturn($result);
        $stmt->method('bind_param')->willReturn(true);

        $this->connect->method('prepare')->willReturn($stmt);

        $_POST['username'] = 'nguyen';
        $_POST['password'] = '123456';

        ob_start();
        include '/xampp/htdocs/W_Sport-main/PHP/Login.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Không tồn tại tên đăng nhập này', $output);
    }
}
