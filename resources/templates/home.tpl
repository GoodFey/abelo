{extends "layout.tpl"}

{block name="content"}

<h1>Добро пожаловать в Abelo</h1>

{* Featured Section *}
<section class="featured-section">
    <h2>О проекте</h2>
    <p>Abelo — это современный блог о веб-разработке, где мы делимся практическими советами, туториалами и статьями о PHP, JavaScript, веб-дизайне и DevOps.</p>
    <p>Присоединяйтесь к нашему сообществу разработчиков и будьте в курсе последних тенденций в веб-индустрии!</p>
</section>

{* Categories Section *}
<section class="categories-section">
    <h2>Категории</h2>

    {if !empty($categories)}
        <div class="grid">
            {foreach $categories as $category}
                <a href="/categories/{$category->slug}" class="category-card">
                    <h3>{$category->name}</h3>
                    <p>{if $category->description}{$category->description}{else}Статьи о {$category->name}{/if}</p>
                    <div class="category-count">{$category->posts_count} {if $category->posts_count == 1}статья{elseif $category->posts_count % 10 < 5 && $category->posts_count % 10 != 0}статьи{else}статей{/if}</div>
                </a>
            {/foreach}
        </div>
    {else}
        <p>Категории не найдены.</p>
    {/if}
</section>

{* Latest Posts by Category Section *}
<section>
    {foreach $postsByCategory as $data}
        {if !empty($data.posts)}
        <div class="category-posts-section">
            <h2>
                <a href="/categories/{$data.category->slug}">{$data.category->name}</a>
            </h2>

            <div class="posts-grid-horizontal">
                {foreach $data.posts as $post}
                    <div class="post-card-horizontal">
                        <img src="/images/placeholder.png" alt="{$post->title}" />

                        <h3>
                            <a href="/posts/{$post->slug}">{$post->title}</a>
                        </h3>

                        <div class="post-meta">
                            <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                            <span> | 👁️ {$post->views}</span>
                        </div>

                        <p class="post-excerpt">{$post->excerpt|default:$post->content|substr:0:150}...</p>

                        <a href="/posts/{$post->slug}" class="btn">Читать →</a>
                    </div>
                {/foreach}
            </div>

            <div class="category-link-center">
                <a href="/categories/{$data.category->slug}" class="btn-secondary">Все статьи в категории →</a>
            </div>
        </div>
        {/if}
    {/foreach}
</section>

{* Popular Posts Section *}
{if !empty($popularPosts)}
    <section class="popular-posts-section">
        <h2>🏆 Популярные статьи</h2>
        <p class="popular-posts-intro">Самые читаемые статьи на блоге</p>

        <div class="posts-list">
            {foreach $popularPosts as $post}
                <div class="post-card">
                    <h3>
                        <a href="/posts/{$post->slug}">{$post->title}</a>
                    </h3>

                    <div class="post-meta">
                        <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                        <span> | 👁️ {$post->views} просмотров</span>
                    </div>

                    <p class="post-excerpt">{$post->excerpt|default:$post->content|substr:0:150}...</p>

                    <a href="/posts/{$post->slug}" class="btn">Читать далее →</a>
                </div>
            {/foreach}
        </div>
    </section>
{/if}

{/block}

