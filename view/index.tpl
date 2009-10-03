<html>
<head>
	<title> 20 Questions </title>
	<script src="static/js/jquery-1.2.6.min.js" type="text/javascript"></script>
	<script src="static/js/jquery.scrollTo-min.js" type="text/javascript"></script>
	<script src="static/js/magic.js" type="text/javascript"></script>
	<link rel="icon" href="static/favicon.ico" type="image/x-icon"> 
	<link rel="shortcut icon" href="static/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="static/css/theme.css" />
	{literal}<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
		try {
			var pageTracker = _gat._getTracker("UA-1951849-7");
			pageTracker._trackPageview();
		} catch(err) {}
	</script>{/literal}
</head>

<body>
	<center>
	<div id="container">
		<div class="header">20 Questions</div>
		<div class="subheader">Please choose a category</div>
		<div class="question_div"></div>
		<div id="lobby">
			<ul id="categories">
				{foreach from=$group_array key=k item=v}
					<li id="{$k}" class="category">{$v}</li>
				{/foreach}
			</ul>
			
			<div id="error"></div>
			
			<div id="left_box" class="response green">Yes</div>
			<div id="right_box" class="response red">No</div>
			<div id="answer"></div>
			
			<div id="answer_submission">
				<h3>Was it...</h3>
				<div class="top10">
					<h4>Top 10</h4>
					<ol class="top10"></ol>
				</div>
				
				<div class="search">
					Or..type as you search:
					<input type="text" id="search_answer" />
					<ul class="possible_word_list"></ul>
				</div>
				
				<div class="add">
					Or...add a word:
					<input type="text" id="add_answer" /> <input id="add_answer_button" type="button" value="Add" />
				</div>
				
				<p>To start all over again...<a href="/index">click here</a></p>
			</div>
			
			<div class="clear"></div>
			<div id="questions">
				<ul></ul>
			</div>
		</div>
	</div>
	</center>
</body>
</html>
<!--
 keyboard for selection of yes or no
 mouse buttons
 arrow keys
-->

