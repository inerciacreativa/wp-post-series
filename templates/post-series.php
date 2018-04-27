<aside class="post-series">
    <h2 class="post-series-header">
        <?php echo $header; ?>
    </h2>
    <?php if ($description): ?>
        <div class="post-series-description">
            <?php echo $description; ?>
        </div>
    <?php endif; ?>
    <nav class="post-series-navigation">
        <ol>
            <?php foreach ($links as $link): ?>
                <li><?php echo $link; ?></li>
            <?php endforeach; ?>
        </ol>
    </nav>
</aside>