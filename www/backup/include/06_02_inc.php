<div class="contentmenu" id="contentmenu">
	<div>
		<ul class="col3">
			<? foreach($this->M_edu->cate AS $ca => $txt) : ?>
			<li id="cm<?=$ca?>"><a href="/edu/lists/cate/<?=$ca?>"><?=$txt?></a></li>
			<? endforeach; ?>
		</ul>
	</div>
</div>

<script type="text/javascript">initClickOn("contentmenu","cm<?=$cate?>");</script>
