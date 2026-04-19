{extends "layout.tpl"}

{block name="content"}

{* Category Page Template *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / <a href="/categories">Категории</a> / {$category->name}
</div>

{* Category Header *}
<h1>{$category->name}</h1>
<p class="category-description">{$category->description|default:'Статьи категории'}</p>

<hr class="page-divider">

{* Posts in Category *}
<section>
    {if count($posts) > 0}
        <h2>Статьи в этой категории ({$total|default:count($posts)})</h2>

        <div class="category-posts-list">
            {foreach $posts as $post}
                <div class="category-post-card">

                    {* Image on the left *}
                    <div class="category-post-image">
                        <img src="/{if $post->image_path}{$post->image_path|thumb:600:338}{else}images/placeholder.png{/if}" alt="{$post->title}" />
                    </div>

                    {* Content on the right *}
                    <div class="category-post-content">
                        <div class="category-post-body">
                            <h3>
                                <a href="/posts/{$post->slug}">{$post->title}</a>
                            </h3>

                            <div class="category-post-meta">
                                <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                                <span> | 👁️ {$post->views}</span>
                                {if $post->author_id}
                                    <span> | ✍️ Автор ID: {$post->author_id}</span>
                                {/if}
                            </div>

                            <p class="category-post-excerpt">{$post->excerpt|default:$post->content|substr:0:180}...</p>

                            <div class="category-post-categories">
                                {foreach $post->getCategories() as $cat}
                                    <a href="/categories/{$cat->slug}">{$cat->name}</a>
                                {/foreach}
                            </div>
                        </div>

                        <div class="category-post-footer">
                            <a href="/posts/{$post->slug}" class="btn">Читать →</a>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>

        {* Pagination *}
        {if $totalPages > 1}
            <div class="pagination">
                {* Previous Page *}
                {if $currentPage > 1}
                    <a href="/categories/{$category->slug}?page=1" title="На первую страницу">« Первая</a>
                    <a href="/categories/{$category->slug}?page={$currentPage - 1}">← Предыдущая</a>
                {else}
                    <span class="disabled">« Первая</span>
                    <span class="disabled">← Предыдущая</span>
                {/if}

                {* Page Numbers with Ellipsis *}
                {foreach $pageNumbers as $pageNum}
                    {if $pageNum === '...'}
                        <span class="ellipsis">...</span>
                    {elseif $pageNum == $currentPage}
                        <span class="active">{$pageNum}</span>
                    {else}
                        <a href="/categories/{$category->slug}?page={$pageNum}">{$pageNum}</a>
                    {/if}
                {/foreach}

                {* Next Page *}
                {if $currentPage < $totalPages}
                    <a href="/categories/{$category->slug}?page={$currentPage + 1}">Следующая →</a>
                    <a href="/categories/{$category->slug}?page={$totalPages}" title="На последнюю страницу">Последняя »</a>
                {else}
                    <span class="disabled">Следующая →</span>
                    <span class="disabled">Последняя »</span>
                {/if}
            </div>

            {* Page Info *}
            <div class="page-info">
                Страница {$currentPage} из {$totalPages} | Показано {count($posts)} из {$total} статей
            </div>
        {/if}
    {else}
        <div class="empty-state">
            <p>В этой категории пока нет статей.</p>
            <p style="margin-top: 1rem;">
                <a href="/categories">← Вернуться к категориям</a>
            </p>
        </div>
    {/if}
</section>

{* Sidebar with other categories *}
<aside class="sidebar-wrapper">
    <div></div>
    <div class="sidebar">
        <h3>Другие категории</h3>
        <ul>
            {* This would require fetching all categories in controller *}
            <li><a href="/categories">← Все категории</a></li>
        </ul>
    </div>
</aside>

{/block}

