<script type="text/javascript" src="scripts/search_list.js"></script>
<div class="col1" style="width:98%;margin-top:5px;">
	<div class="box">
	<div class="box_title"><h3><?php echo getLang('Search or double click an item to view details',$lang) ?></h3></div>
		<div class="box_1">
			<ul class="hot_box">
			</ul>
		</div>
		<div style="line-height:28px;padding-left:10px;margin:0 9px 0 10px;background:url(images/table-bar.png) repeat-x" class="search_bar">
		 <b>Quick Search</b> &nbsp <input type="radio" id="cur_site" name="s_site" checked><label for="cur_site"><?php echo $site ?></label> &nbsp <input type="radio" id="global" name="s_site" value="Global"><label for="global">Global</label> &nbsp <input type="text" style="width:200px" id="top_search_txt">&nbsp<input type="button" value="Search" id="top_search_btn">&nbsp<input type="button" value="Clear" id="top_search_cl">
		</div>
		<div style="line-height:28px;padding-left:10px;margin:0 9px 0 10px;">
			<a class="s_letter" href="#">A</a>
			<a class="s_letter" href="#">B</a>
			<a class="s_letter" href="#">C</a>
			<a class="s_letter" href="#">D</a>
			<a class="s_letter" href="#">E</a>
			<a class="s_letter" href="#">F</a>
			<a class="s_letter" href="#">G</a>
			<a class="s_letter" href="#">H</a>
			<a class="s_letter" href="#">I</a>
			<a class="s_letter" href="#">J</a>
			<a class="s_letter" href="#">K</a>
			<a class="s_letter" href="#">L</a>
			<a class="s_letter" href="#">M</a>
			<a class="s_letter" href="#">N</a>
			<a class="s_letter" href="#">O</a>
			<a class="s_letter" href="#">P</a>
			<a class="s_letter" href="#">Q</a>
			<a class="s_letter" href="#">R</a>
			<a class="s_letter" href="#">S</a>
			<a class="s_letter" href="#">T</a>
			<a class="s_letter" href="#">U</a>
			<a class="s_letter" href="#">V</a>
			<a class="s_letter" href="#">W</a>
			<a class="s_letter" href="#">X</a>
			<a class="s_letter" href="#">Y</a>
			<a class="s_letter" href="#">Z</a>
			<a class="s_letter" href="#">0</a>
			<a class="s_letter" href="#">1</a>
			<a class="s_letter" href="#">2</a>
			<a class="s_letter" href="#">3</a>
			<a class="s_letter" href="#">4</a>
			<a class="s_letter" href="#">5</a>
			<a class="s_letter" href="#">6</a>
			<a class="s_letter" href="#">7</a>
			<a class="s_letter" href="#">8</a>
			<a class="s_letter" href="#">9</a>
		</div>
		<div class="grid">
		<table id="mygrid" style="display:none;"></table>
		</div>
	</div>
</div>