<?php
ob_start();
require_once "config/database.php";
require_once "config/sweetalert.php";

$memtype = $controller->getData('Type');
$member_list = $controller->getData('Member');

if(isset($_POST['submit']) && $_POST['submit'] == 'add'){
    $memberId = $_POST['member_id'];
    // อัพโหลดรูปภาพ
    $photo = $controller->uploadMemberImage($_FILES['member_photo'], $memberId);
    
    if($photo !== false){
        // เตรียมข้อมูลในรูปแบบ Array
        $data = [
            ':member_id'      => $memberId,
            ':type_id'        => $_POST['type_id'],
            ':prefix'         => $_POST['prefix'],
            ':member_fname'   => $_POST['member_fname'],
            ':member_lname'   => $_POST['member_lname'],
            ':member_email'   => $_POST['member_email'],
            ':member_phone'   => $_POST['member_phone'],
            ':member_address' => $_POST['member_address'],
            ':member_photo'   => $photo,
            ':member_status'  => $_POST['member_status']
        ];

        $status = $controller->insertMember($data);
        
        if($status){
            $alert = new SweetAlert('Success','เพิ่มข้อมูลสมาชิกเรียบร้อยแล้ว','success');
            $alert->setRedirectUrl('index.php?menu=2');
            echo $alert;
        }else{
            $alert = new SweetAlert('Error','ไม่สามารถเพิ่มข้อมูลสมาชิกได้','error');
            $alert->setRedirectUrl('index.php?menu=2');
            echo $alert;
        }
    }else{
        $alert = new SweetAlert('Error','การอัพโหลดรูปภาพล้มเหลว','error');
        $alert->setRedirectUrl('index.php?menu=2');
        echo $alert;
    }
}

?>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>จัดการสมาชิก</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">รายการสมาชิก</h3>
                <div class="card-tools"> 
                    <button type="button" 
                        class="btn btn-primary" 
                        data-toggle="modal" 
                        data-target="#modal-member">
                        <i class="fas fa-plus"></i> เพิ่มสมาชิก
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover table-striped mb-0">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 50px">#</th>
                            <th>รูป</th>
                            <th>รหัสสมาชิก</th>
                            <th>ชื่อ-นามสกุล</th>
                            <th>อีเมล</th>
                            <th>เบอร์โทร</th>
                            <th>สถานะ</th>
                            <th>วันที่สมัคร</th>
                            <th style="width: 150px">การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        while($row = $member_list->fetch(PDO::FETCH_ASSOC)){ ?>
                        <tr class="text-center">
                            <td class="align-middle"><?php echo $i; ?></td>
                            <td class="align-middle">
                                <img src="uploads/img/<?php echo $row['member_photo']; ?>" class="img-circle elevation-2"
                                    alt="User Image" style="width: 40px; height: 40px;">
                            </td>
                            <td class="align-middle"><?php echo $row['member_id']; ?></td>
                            <td class="text-left align-middle">
                                <?php echo $row['prefix'].$row['member_fname']." "
                                    .$row['member_fname']; ?></td>
                            <td class="align-middle"><?php echo $row['member_email']; ?></td>
                            <td class="align-middle"><?php echo $row['member_phone']; ?></td>
                            <td class="align-middle">
                                <span class="badge badge-success">
                                    <?php echo ($row['member_status'] == 1) ? 'ใช้งาน' : 'ระงับ'; ?>
                                </span>
                            </td>
                            <td class="align-middle">
                                <?php echo date('d/m/Y', strtotime($row['created_at'])); ?>
                            </td>
                            <td class="align-middle">
                                <a href="#" class="btn btn-warning btn-sm" title="แก้ไข">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm" title="ลบ"
                                    onclick="return confirm('ยืนยันการลบข้อมูล?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>


<div class="modal fade" id="modal-member" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="memberModalLabel text-white">
                    <i class="fas fa-user-plus mr-2"></i> จัดการข้อมูลสมาชิก
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="index.php?menu=2" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="member_id">รหัสสมาชิก (Auto)</label>
                                <input type="text" class="form-control" id="member_id" name="member_id"
                                    placeholder="ระบบจะสร้างให้อัตโนมัติ" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type_id">ประเภทสมาชิก</label>
                                <select class="form-control" name="type_id" id="type_id" required>
                                    <option value="">-- เลือกประเภท --</option>
                                    <?php while($row = $memtype->fetch(PDO::FETCH_ASSOC)){ ?>
                                    <option value="<?php echo $row['type_id']; ?>">
                                        <?php echo $row['type_name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="prefix">คำนำหน้า</label>
                                <select class="form-control" name="prefix" id="prefix" required>
                                    <option value="นาย">นาย</option>
                                    <option value="นาง">นาง</option>
                                    <option value="นางสาว">นางสาว</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="member_fname">ชื่อ</label>
                                <input type="text" class="form-control" name="member_fname" placeholder="กรอกชื่อ"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="member_lname">นามสกุล</label>
                                <input type="text" class="form-control" name="member_lname" placeholder="กรอกนามสกุล"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="member_email">อีเมล</label>
                                <input type="email" class="form-control" name="member_email"
                                    placeholder="example@mail.com" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="member_phone">เบอร์โทรศัพท์</label>
                                <input type="text" class="form-control" name="member_phone" placeholder="08x-xxxxxxx"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="member_address">ที่อยู่</label>
                                <textarea class="form-control" name="member_address" rows="2"
                                    placeholder="กรอกที่อยู่ปัจจุบัน"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="member_photo">รูปภาพสมาชิก</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="member_photo" name="member_photo"
                                        accept="image/*">
                                    <label class="custom-file-label" for="member_photo">เลือกไฟล์</label>
                                </div>
                                <div class="mt-2 text-center">
                                    <img id="preview" src="https://via.placeholder.com/120" alt="Preview"
                                        class="img-thumbnail" style="max-height: 120px;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>สถานะการใช้งาน</label>
                                <div class="mt-2">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" id="status1"
                                            name="member_status" value="1" checked>
                                        <label for="status1" class="custom-control-label">ใช้งาน</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input" type="radio" id="status0"
                                            name="member_status" value="0">
                                        <label for="status0" class="custom-control-label text-danger">ระงับ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิดหน้าต่าง</button>
                    <button type="submit" class="btn btn-success px-4" name="submit" value="add">
                        <i class="fas fa-save mr-1"></i> บันทึกข้อมูล
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';

    /**
     * ฟังก์ชันสำหรับโหลดรหัสพนักงานอัตโนมัติ
     */
    function loadAutoEmpId() {
        var memidInput = document.getElementById('member_id');

        if (!memidInput) {
            console.error('ไม่พบช่องกรอกรหัสพนักงาน');
            return;
        }

        // แสดงข้อความ Loading
        memidInput.value = 'กำลังโหลด...';

        // URL ของ API
        var apiUrl = 'pages/get_auto_id.php';

        // เรียก AJAX ด้วย fetch API
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
            cache: 'no-cache'
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('HTTP error! status: ' + response.status);
            }
            return response.json();
        })
        .then(function(data) {
            if (data.success) {
                // ตรวจสอบว่า API ส่ง memid หรือ member_id มาให้
                memidInput.value = data.memid || data.member_id;
            } else {
                console.error('API Error:', data.message);
                alert('เกิดข้อผิดพลาด: ' + data.message);
                memidInput.value = '';
            }
        })
        .catch(function(error) {
            console.error('Fetch Error:', error);
            alert('ไม่สามารถโหลดรหัสสมาชิกได้\nกรุณาลองใหม่อีกครั้ง');
            memidInput.value = '';
        });
    }

    /**
     * ฟังก์ชันสำหรับแสดงภาพตัวอย่าง (Image Preview)
     * และเปลี่ยนชื่อ Label ตามไฟล์ที่เลือก
     */
    function initImagePreview() {
        var photoInput = document.getElementById('member_photo');
        var previewImg = document.getElementById('preview');

        if (photoInput) {
            photoInput.addEventListener('change', function(e) {
                var file = e.target.files[0];
                var label = e.target.nextElementSibling; // .custom-file-label

                if (file) {
                    // เปลี่ยนชื่อ Label เป็นชื่อไฟล์
                    if (label) label.innerText = file.name;

                    // อ่านไฟล์ภาพเพื่อแสดง Preview
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        if (previewImg) previewImg.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // ถ้าไม่ได้เลือกไฟล์ ให้กลับเป็นค่าเริ่มต้น
                    if (label) label.innerText = 'เลือกไฟล์';
                    if (previewImg) previewImg.src = 'https://via.placeholder.com/120';
                }
            });
        }
    }

    /**
     * ฟังก์ชันสำหรับล้างข้อมูลเมื่อปิด Modal
     */
    function clearModalData() {
        var memidInput = document.getElementById('member_id');
        if (memidInput) {
            memidInput.value = '';
        }

        var form = document.querySelector('#modal-member form');
        if (form) {
            form.reset();
        }

        // เพิ่มการล้างรูป Preview และ Label ไฟล์
        var previewImg = document.getElementById('preview');
        if (previewImg) previewImg.src = 'https://via.placeholder.com/120';

        var label = document.querySelector('.custom-file-label');
        if (label) label.innerText = 'เลือกไฟล์';
    }

    /**
     * เริ่มต้น Event Listeners
     */
    function initAutoID() {
        var modalElement = document.getElementById('modal-member');
        
        if (!modalElement) {
            console.error('ไม่พบ Modal #modal-member');
            return;
        }

        // เรียกใช้งาน Image Preview
        initImagePreview();

        // วิธีที่ 1: jQuery Events
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.modal !== 'undefined') {
            jQuery('#modal-member').on('show.bs.modal', function(e) {
                loadAutoEmpId();
            });

            jQuery('#modal-member').on('hidden.bs.modal', function(e) {
                clearModalData();
            });
        }
        
        // วิธีที่ 2: Vanilla JS Events (Fallback)
        modalElement.addEventListener('show.bs.modal', function(e) {
            loadAutoEmpId();
        });

        modalElement.addEventListener('hidden.bs.modal', function(e) {
            clearModalData();
        });
        
        // วิธีที่ 3: Button Click Fallback
        setTimeout(function() {
            var btn = document.querySelector('[data-toggle="modal"][data-target="#modal-member"]');
            
            if (btn) {
                btn.addEventListener('click', function() {
                    setTimeout(function() {
                        loadAutoEmpId();
                    }, 400);
                });
            }
        }, 500);
    }

    // รอให้ DOM โหลดเสร็จก่อน
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initAutoID();
        });
    } else {
        initAutoID();
    }

})();
</script>