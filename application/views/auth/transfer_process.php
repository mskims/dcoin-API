
<meta http-equiv="refresh" content="2; url=<?=$this->transfer_info["redirect_url"]?>"></meta>
<div class="form-group panel panel-success">
		<div class="panel-heading">
			송금 완료
		</div>
		<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>보내는 분</th>
						<th>받는 분</th>
						<th>받는 계좌</th>
						<th class="text-right">금액</th>
						<th>처리 시각</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?=$this->transfer_info["user_from_name"]?></td>
						<td><?=$this->transfer_info["user_to_name"]?></td>
						<td><?=$this->transfer_info["user_to_account_number"]?></td>
						<td class="text-right"><?=$this->transfer_info["money"]?></td>
						<td><?=$this->transfer_info["sent_at"]?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
	<div class="form-group">
		잠시 후 <strong>bet.kimminseok.info</strong> 로 이동합니다
	</div>