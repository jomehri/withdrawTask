@if(isset($rows) && $rows->count())
	<div class = "h4 mt-3">
		Result:
	</div>
	<table class = "table table-dark">
		<thead>
		<tr>
			<td>Date</td>
			<td>UserId</td>
			<td>Type</td>
			<td>Action</td>
			<td>Amount</td>
			<td>Currency</td>
			<td class = "bg-danger">Calculated Tax</td>
		</tr>
		</thead>
		<tbody>
		@foreach($rows as $row)
			<tr>
				<td>{{ $row[0] }}</td>
				<td>{{ $row[1] }}</td>
				<td>{{ $row[2] }}</td>
				<td>{{ $row[3] }}</td>
				<td>{{ $row[4] }}</td>
				<td>{{ $row[5] }}</td>
				<td class = "bg-danger">// TODO</td>
			</tr>
		@endforeach        </tbody>
	</table>
@endif
