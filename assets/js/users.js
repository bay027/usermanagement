
(function () {
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
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(function (data) {
                if (data.success) {
                    // ตรวจสอบว่า API ส่ง memid หรือ member_id มาให้
                    memidInput.value = data.memid || data.member_id;
                } else {
                    console.error('API Error:', data.message);
                    alert('เกิดข้อผิดพลาด: ' + data.message);
                    memidInput.value = '';
                }
            })
            .catch(function (error) {
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
            photoInput.addEventListener('change', function (e) {
                var file = e.target.files[0];
                var label = e.target.nextElementSibling; // .custom-file-label

                if (file) {
                    // เปลี่ยนชื่อ Label เป็นชื่อไฟล์
                    if (label) label.innerText = file.name;

                    // อ่านไฟล์ภาพเพื่อแสดง Preview
                    var reader = new FileReader();
                    reader.onload = function (event) {
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
            jQuery('#modal-member').on('show.bs.modal', function (e) {
                loadAutoEmpId();
            });

            jQuery('#modal-member').on('hidden.bs.modal', function (e) {
                clearModalData();
            });
        }

        // วิธีที่ 2: Vanilla JS Events (Fallback)
        modalElement.addEventListener('show.bs.modal', function (e) {
            loadAutoEmpId();
        });

        modalElement.addEventListener('hidden.bs.modal', function (e) {
            clearModalData();
        });

        // วิธีที่ 3: Button Click Fallback
        setTimeout(function () {
            var btn = document.querySelector('[data-toggle="modal"][data-target="#modal-member"]');

            if (btn) {
                btn.addEventListener('click', function () {
                    setTimeout(function () {
                        loadAutoEmpId();
                    }, 400);
                });
            }
        }, 500);
    }

    // รอให้ DOM โหลดเสร็จก่อน
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initAutoID();
        });
    } else {
        initAutoID();
    }

})();