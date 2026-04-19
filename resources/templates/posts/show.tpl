{extends "layout.tpl"}

{block name="content"}

{* Single Post Page Template *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / <a href="/posts">Статьи</a> / {$post->title}
</div>

{* Post Header *}
<article>
    <h1>{$post->title}</h1>

    {* Full-width Image *}
    <div class="post-image-full-width">
        <img src="/{if $post->image_path}{$post->image_path|thumb:1200:675}{else}images/placeholder.png{/if}" alt="{$post->title}" />
    </div>

    <div class="post-meta-show">
        <span>📅 Опубликовано: {$post->published_at|date_format:'%d %B %Y'}</span>
        <span> | 👁️ Просмотров: {$post->views}</span>
        {if $post->author_id}
            <span> | ✍️ Автор ID: {$post->author_id}</span>
        {/if}
    </div>

    {* Categories *}
    {if count($categories) > 0}
        <div class="post-categories-show">
            <strong>Категории:</strong>
            {foreach $categories as $category}
                <a href="/categories/{$category->slug}" class="badge">{$category->name}</a>
            {/foreach}
        </div>
    {/if}

    {* Post Content *}
    <div class="content-body">
        {$post->content|markdown}
    </div>
</article>

{* Navigation *}
<div class="post-navigation">
    <div>
        <a href="/posts" class="btn">← Все статьи</a>
    </div>
    <div>
        <a href="/" class="btn">На главную →</a>
    </div>
</div>

{* Similar Posts Section *}
{if count($similarPosts) > 0}
    <section class="similar-posts-section">
        <h2>📚 Похожие статьи</h2>
        <p class="section-description">
            Статьи из {if count($categories) > 0}категории {$categories[0]->name}{else}похожих категорий{/if}
        </p>

        <div class="posts-grid">
            {foreach $similarPosts as $similar}
                <div class="post-card">
                    <h3>
                        <a href="/posts/{$similar->slug}">{$similar->title}</a>
                    </h3>

                    <div class="post-meta">
                        <span>📅 {$similar->published_at|date_format:'%d.%m.%Y'}</span>
                        <span> | 👁️ {$similar->views}</span>
                    </div>

                    <p class="post-excerpt">{$similar->excerpt|default:$similar->content|substr:0:120}...</p>

                    <a href="/posts/{$similar->slug}" class="btn">Читать →</a>
                </div>
            {/foreach}
        </div>
    </section>
{/if}

{* Recommended Posts Section *}
{if count($recommendedPosts) > 0}
    <section class="recommended-posts-section">
        <h2>⭐ Рекомендуемые статьи</h2>
        <p class="section-description">
            На основе ваших интересов
        </p>

        <div class="posts-grid">
            {foreach $recommendedPosts as $recommended}
                <div class="post-card">
                    <h3>
                        <a href="/posts/{$recommended->slug}">{$recommended->title}</a>
                    </h3>

                    <div class="post-meta">
                        <span>📅 {$recommended->published_at|date_format:'%d.%m.%Y'}</span>
                        <span> | 👁️ {$recommended->views}</span>
                    </div>

                    <p class="post-excerpt">{$recommended->excerpt|default:$recommended->content|substr:0:120}...</p>

                    <a href="/posts/{$recommended->slug}" class="btn">Читать →</a>
                </div>
            {/foreach}
        </div>
    </section>
{/if}

{/block}

