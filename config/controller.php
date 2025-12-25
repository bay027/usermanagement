<?php

class Controller
{
    private $db;

    public function __construct($conn)
    {
        $this->db = $conn;
        // echo "Select Controller";
    }

    public function insertMember($data)
    {
        try {
            $sql = "INSERT INTO members 
                    (member_id, type_id, prefix, member_fname, member_lname,member_email,
                     member_phone, member_address, member_photo, member_status) 
                    VALUES 
                    (:member_id, :type_id, :prefix, :member_fname, :member_lname, :member_email,
                     :member_phone, :member_address, :member_photo, :member_status)";

            $stmt = $this->db->prepare($sql);            
            $result = $stmt->execute($data);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function uploadMemberImage($file, $memberId)
    {
        try {
            // ตรวจสอบว่ามีการอัพโหลดไฟล์หรือไม่
            if (!isset($file) || $file['error'] == UPLOAD_ERR_NO_FILE) {
                return 'default-avatar.png'; // รูปภาพเริ่มต้น
            }

            // ตรวจสอบข้อผิดพลาด
            if ($file['error'] !== UPLOAD_ERR_OK) {
                throw new Exception('Upload error: ' . $file['error']);
            }

            // ตรวจสอบประเภทไฟล์
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            $fileType = $file['type'];
            
            if (!in_array($fileType, $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG and GIF allowed.');
            }

            // ตรวจสอบขนาดไฟล์ (จำกัดที่ 5MB)
            $maxSize = 5 * 1024 * 1024; // 5MB
            if ($file['size'] > $maxSize) {
                throw new Exception('File too large. Maximum size is 5MB.');
            }

            // สร้างโฟลเดอร์ถ้ายังไม่มี
            $uploadDir = 'uploads/img/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // สร้างชื่อไฟล์ใหม่
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = $memberId . '_' . time() . '.' . $extension;
            $uploadPath = $uploadDir . $newFileName;

            // ย้ายไฟล์
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                return $newFileName;
            } else {
                throw new Exception('Failed to move uploaded file.');
            }

        } catch (Exception $e) {
            echo "Upload Error: " . $e->getMessage();
            return false;
        }
    }

    public function getData($param)
    {
        try {
            switch ($param) {
                case 'Type':
                    $sql = "SELECT * FROM member_types";
                    break;
                case 'Member':
                    $sql = "SELECT * FROM members m INNER JOIN member_types mt
                                ON m.type_id=mt.type_id
                                ORDER BY m.member_id DESC";
                    break;
            }
            $result = $this->db->query($sql);
            return $result;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getNextMemId()
    {
        try {
            // ดึง id ล่าสุดจากฐานข้อมูล
            $sql = "SELECT member_id
                        FROM members
                        ORDER BY member_id
                        DESC LIMIT 1";
            $result = $this->db->query($sql);

            if ($result->rowCount() > 0) {
                // ถ้ามีข้อมูลอยู่แล้ว
                $row    = $result->fetch(PDO::FETCH_ASSOC);
                $lastId = $row['member_id'];

                // แยกเอาตัวเลขออกมา (สมมติรูปแบบ MEM000000001)
                $number = (int) substr($lastId, 9);

                // บวกเพิ่ม 1
                $newNumber = $number + 1;

                // สร้าง ID ใหม่ในรูปแบบ MEM + เลข 9 หัก (เช่น MEM000000001)
                $newId = 'MEM' . str_pad($newNumber, 9, '0', STR_PAD_LEFT);

                return $newId;
            } else {
                // ถ้ายังไม่มีข้อมูล เริ่มต้นที่ MEM000000001
                return 'MEM000000001';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}
