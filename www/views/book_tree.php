<?php
require_once "book_lib.php";

$conn = db_connect();

$tree = box_tree($conn);
?>
<ul>
	<?php foreach ($tree as $box_id => $box): ?>
		<li class="box">
			<span class="box-label">
				<?= htmlspecialchars($box['name']) ?> 
			</span>

			<?php if (!empty($box['children'])): ?>
				<ul class="book-list">
					<?php foreach ($box['children'] as $book): ?>
						<li>
							<a href="#" class="tree-book-link" data-id="<?= $book['id'] ?>">
							<?php
								$title  = htmlspecialchars($book['title']);
								$author = trim(htmlspecialchars($book['author']));

								if (!empty($author)) {
									echo $title . "</a> <span class=\"author\">(" . htmlspecialchars($author) . ")</span>";	
								} else {
									echo $title . "</a>";
								}
							?>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php else: ?>
				<div><em>(No books)</em></div>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>
<?php db_disconnect($conn); ?>

