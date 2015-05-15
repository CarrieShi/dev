<?php 
echo "<script> 
parent.document.getElementById('msg').innerHTML = 'iframe式ajax调用成功!'; 
alert('您输入的是：{$_POST['username']}'); 
window.setTimeout(function(){ 
	parent.window.location.reload(); 
},3000); 
</script>";
