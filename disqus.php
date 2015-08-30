<div class="vsf-comments-wrapper">
	<?php
	$disqusId = get_option("vs_flickr_disqus_id");
	if($disqusId != false && $disqusId != "")
		disqus_embed($disqusId);
	?>
</div>