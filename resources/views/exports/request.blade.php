
<table style="border:1px solid #000;">
    <thead>
    <tr>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Tiêu đề</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Nội dung</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Ưu tiên</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Chi phí dự tính</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Chi phí</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Nguồn dự tính</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Nguồn phí</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Loại yêu cầu</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Trạng thái</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Người xử lý</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Ngày tạo</th>
        <th style="border:1px solid #000; font-size: 16px; font-weight: bold; background-color:aqua;">Ngày hoàn thành</th>
    </tr>
    </thead>
    <tbody style="border:1px solid">
    @foreach($requests as $request)
        <tr>
            <td style="border:1px solid #000;">{{ $request->subject }}</td>
            <td style="border:1px solid #000;">{{ $request->content }}</td>
            <td style="border:1px solid #000;">{{ $request->priority == 'L' ? 'Thấp' : ($request->priority == 'M' ? 'Vừa' : 'Cao') }}</td>
            <td style="border:1px solid #000;">{{ $request->cost }}</td>
            <td style="border:1px solid #000;">{{ $request->final_cost }}</td>
            <td style="border:1px solid #000;">{{ $request->src_tp ? $request->src_tp->resource_name : '' }}</td>
            <td style="border:1px solid #000;">{{ $request->fn_src_tp ? $request->fn_src_tp->resource_name : '' }}</td>
            <td style="border:1px solid #000;">{{ $request->type ? $request->type->type_name : '' }}</td>
            <td style="border:1px solid #000;">{{ $request->status == 'A' ? 'Yêu cầu mới' :
              ($request->status == 'B' ? 'Tiếp nhận' : 
              ($request->status == 'C' ? 'Gia hạn' :
              ($request->status == 'D' ? 'Đang xử lý' : 
              ($request->status == 'E' ? 'Chuyển xử lý' : 
              ($request->status == 'F' ? 'Hoàn thành' : 'Từ chối'))))) }}</td>
            <td style="border:1px solid #000;">{{ $request->handler->name }}</td>
            <td style="border:1px solid #000;">{{ $request->created_at }}</td>
            <td style="border:1px solid #000;">{{ $request->complete_date }}</td>
        </tr>
    @endforeach
    </tbody>
</table>