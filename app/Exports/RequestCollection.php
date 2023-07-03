<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Main\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RequestCollection implements FromCollection, WithColumnFormatting, WithMapping, WithHeadings, WithStyles, ShouldAutoSize, WithColumnWidths
{
    public function collection()
    {
        return Request::export();
    }

    public function map($request): array
    {
        return [
            $request->subject,
            strip_tags($request->content),
            $request->priority == 'L' ? 'Thấp' : ($request->priority == 'M' ? 'Vừa' : 'Cao'),
            $request->cost,
            $request->final_cost,
            $request->src_tp ? $request->src_tp->resource_name : '',
            $request->fn_src_tp ? $request->fn_src_tp->resource_name : '',
            $request->type ? $request->type->type_name : '',
            $request->status == 'A' ? 'Yêu cầu mới' :
              ($request->status == 'B' ? 'Tiếp nhận' : 
              ($request->status == 'C' ? 'Gia hạn' :
              ($request->status == 'D' ? 'Đang xử lý' : 
              ($request->status == 'E' ? 'Chuyển xử lý' : 
              ($request->status == 'F' ? 'Hoàn thành' : 'Từ chối'))))),
            $request->requester->name,
            $request->requester->telephone,
            $request->department->department_name,
            $request->handler != null ? $request->handler->name : "",
            Date::stringToExcel($request->created_at),
            Date::stringToExcel($request->complete_date)
        ];
    }

    public function headings(): array
    {
        return [
            'Tiêu đề',
            'Nội dung',
            'Ưu tiên',
            'Chi phí được duyệt',
            'Chi phí thực tế',
            'Nguồn vốn được duyệt',
            'Nguồn vốn thực tế',
            'Loại yêu cầu',
            'Trạng thái',
            'Người tạo',
            'Số điện thoại',
            'Phòng ban',
            'Người xử lý',
            'Ngày tạo',
            'Ngày hoàn thành',
        ];
    }
    
    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'O' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 55,
            'B' => 45,            
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true], 'border' => 'thin'],
        ];
    }
}
