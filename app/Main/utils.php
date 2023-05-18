<?php

namespace App\Main;

class Utils {

    /**
     * Phân quyền
     */
    const NHAN_VIEN = 0;
    const PHO_QUAN_LY = 1;
    const QUAN_LY = 2;
    const ROLE_LIST = [
        'NHAN_VIEN' => 0,
        'PHO_QUAN_LY' => 1,
        'QUAN_LY' => 2
    ];

    /**
     * Trang thái xử lý
     */
    const YEU_CAU_MOI = "A";
    const TIEP_NHAN = "B";
    const GIA_HAN = "C";
    const DANG_XU_LY = "D";
    const CHUYEN_XU_LY = "E";
    const HOAN_THANH = "F";
    const TU_CHOI = "X";

    const STATUS_LIST = array(
        "A" => "Yêu cầu mới",
        "B" => "Tiếp nhận",
        "C" => "Gia hạn",
        "D" => "Đang xử lý",
        "E" => "Chuyển xử lý",
        "F" => "Hoàn thành",
        "X" => "Từ chối"
    );

    const STATUS_COLOR = array(
        "A" => "#96a5d0",
        "B" => "#f78b00",
        "C" => "#f78b00",
        "D" => "#f78b00",
        "E" => "#f78b00",
        "F" => "#13b176",
        "X" => "#ce3131"
    );

    /**
     * Trang thái xử lý phụ
     */
    const CHO_XU_LY = "P";
    const HOAN_THANH_XU_LY = "F";

    /**
     * Loại email thông báo
     */
    const MAIL_YC_MOI = 1;
    const MAIL_YC_XU_LY = 2;
    const MAIL_THONG_BAO_DA_YC_XU_LY = 3;
    const MAIL_THONG_BAO_HOAN_THANH = 4;

    /**
     * Độ ưu tiên
     */
    const UU_TIEN_THAP = 'L';
    const UU_TIEN_BINH = 'M';
    const UU_TIEN_CAO = 'H';

    /**
     * Gia Hạn
     */
    const KHONG_GIA_HAN = 'N';
    const YEU_CAU_GIA_HAN = 'R';
    const DONG_Y_GIA_HAN = 'A';
    const TU_CHOI_GIA_HAN = 'D';
}
