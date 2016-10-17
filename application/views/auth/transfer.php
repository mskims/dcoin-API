<form action="/auth/transfer_process" method="POST">
	<input type="hidden" name="csrf_token" value="<?=$this->csrf_token?>">
	<input type="hidden" name="redirect_url" value="<?=$this->redirect_url?>">
	<input type="hidden" name="hash" value="<?=$this->hash?>">
	<div class="form-group alert alert-info">
		<strong><?=$this->app_info["name"]?></strong> 앱이 다음과 같은 송금을 요청합니다.
	</div>
	<div class="form-group panel panel-default">
		<div class="panel-heading">
			송금 정보
		</div>
		<div class="panel-body">
			<table class="table table-hover">
				<thead>
					<tr>
						<th>보내는 분</th>
						<th>받는 분</th>
						<th>받는 계좌</th>
						<th class="text-right">금액</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?=$this->transfer["user_from_name"]?></td>
						<td><?=$this->transfer["user_to_name"]?></td>
						<td><?=$this->transfer["user_to_account_number"]?></td>
						<td class="text-right"><?=$this->transfer["money"]?></td>
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
	<div class="form-group">
		<input type="text" name="account_number" placeholder="아이디" class="form-control" required="">
	</div>
	<div class="form-group">
		<input type="password" name="pw" placeholder="비밀번호" class="form-control" required="">
	</div>
	<?php if(!empty($this->errors)){ ?>
	<div class="form-group errors">
		 <?php foreach($this->errors as $error){ echo $error."<br>"; } ?>
	</div>
	<?php } ?>
	<button class="btn btn-info">송금</button>
	<button class="btn btn-danger">취소</button>
</form>