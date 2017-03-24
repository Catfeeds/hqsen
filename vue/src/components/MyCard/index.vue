<template>
	<div class="my-card">
		<div class="card-header">
			2012-12-12 12：12：12 （总经理审批）
			<span class="card-status">已驳回</span>
		</div>
		<div class="card-body">
			审批备注：这是审批人的备注
		</div>
	</div>
</template>

<style>
	.my-card {
		margin-top: 20px;
		border: 1px solid #ccc;
		color: #333;
		font-size: 14px;
		& .card-header {
			border-bottom: 1px solid #ccc;
			padding: 10px;
			& .card-status {
				float: right;
			}
		}
		& .card-body {
			padding: 10px;
		}
	}
</style>

<script>
  export default {
    props: {
      data: Object
    }
  }
</script>
