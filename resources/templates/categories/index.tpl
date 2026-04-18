{extends "layout.tpl"}

{block name="content"}

{* Categories Index Page *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / Категории
</div>

<h1>Все категории</h1>
<p style="color: #64748b; margin-bottom: 2rem;">Выберите категорию, чтобы увидеть связанные статьи</p>

{if count($categories) > 0}
    <div class="grid">
        {foreach $categories as $category}
            <a href="/categories/{$category->slug}" class="category-card">
                <h3>{$category->name}</h3>
                <p>{if $category->description}{$category->description}{else}Статьи о {$category->name}{/if}</p>
                <div class="category-count">{$category->posts_count} {if $category->posts_count == 1}статья{elseif $category->posts_count % 10 < 5 && $category->posts_count % 10 != 0}статьи{else}статей{/if}</div>
                <div style="margin-top: 1rem; font-size: 0.875rem; color: #3b82f6;">
                    Перейти к статьям →
                </div>
            </a>
        {/foreach}
    </div>
{else}
    <div style="background-color: #fff3cd; padding: 2rem; border-radius: 0.5rem; text-align: center;">
        <p>Категории не найдены.</p>
    </div>
{/if}

{* Popular Posts Section *}
{if !empty($popularPosts)}
    <section class="popular-posts-section" style="margin-top: 3rem;">
        <h2>🏆 Популярные статьи</h2>
        <p class="popular-posts-intro" style="color: #64748b; margin-bottom: 2rem;">Самые читаемые статьи на блоге</p>

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

