<main style="min-height: 100vh; grid-area: content;">

    <h1 style="text-align: center; margin-top: 32px;">Bem vindo ao meu blog</h1>
    <div class="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-title">
                    <a href="templates/post.php?id=<?= $post['id'] ?>"><?= $post['title']; ?></a>
                </div>
                <div class="post-body">
                    <div class="post-img">
                        <img src="images/<?= $post['img']; ?>" alt="Imagem post <?= $post['id']; ?>">
                    </div>
                    <div class="post-description">
                        <?= $post['description']; ?>
                    </div>
                </div>
                <div class="post-footer">
                    <div class="post-tags">
                        <?php foreach ($post['tags'] as $tag): ?>
                            <span class="post-tag"><?= $tag ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

</main>