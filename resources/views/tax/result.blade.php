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
				<td>{{ $row['date'] }}</td>
				<td>{{ $row['userId'] }}</td>
				<td>{{ $row['type'] }}</td>
				<td>{{ $row['action'] }}</td>
				<td>{{ $row['amount'] }}</td>
				<td>{{ $row['currency'] }}</td>
				<td class = "bg-danger">{{ $row['tax'] }}</td>
			</tr>
		@endforeach        </tbody>
	</table>
@endif
