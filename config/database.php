<?php
class Database {
    // เก็บ Instance ของคลาสนี้
    private static $instance = null;
    private $conn;

    // ตั้งค่าการเชื่อมต่อ
    private $HOST_NAME = "localhost";
    private $DB_NAME   = "membersdb000";
    private $CHAR_SET  = "utf8";
    private $USERNAME  = "root000";
    private $PASSWORD  = "123456789";

    // Constructor ต้องเป็น private เพื่อป้องกันการสร้าง Object จากภายนอก (new Database)
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->HOST_NAME};
                    dbname={$this->DB_NAME};
                    charset={$this->CHAR_SET}";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $this->conn = new PDO($dsn, $this->USERNAME, $this->PASSWORD, $options);
            
        } catch (PDOException $e) {
            die("ไม่สามารถเชื่อมต่อฐานข้อมูลได้: " . $e->getMessage());
        }
    }

    // Method สำหรับดึง Instance (ถ้ายังไม่มีให้สร้าง ถ้ามีแล้วให้ส่งค่าเดิมกลับ)
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Method สำหรับส่งคืน Connection
    public function getConnection() {
        return $this->conn;
    }

    // ป้องกันการ Clone object
    private function __clone() {}
    
    // ป้องกันการ Unserialize
    public function __wakeup() {}
}

require_once "controller.php";
$db = Database::getInstance(); // เรียก Instance ของ DB
$conn = $db->getConnection();  // ดึง Connection ออกมา
$controller = new Controller($conn); // ส่ง $conn ที่ดึงออกมาให้ Controller
?>