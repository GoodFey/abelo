{extends "layout.tpl"}

{block name="content"}

{* Posts Index Page *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / Статьи
</div>

<h1>Все статьи</h1>
<p class="posts-description">Всего опубликовано {$total|default:0} статей</p>

{* Sort Controls *}
<div class="sort-controls-block">
    <strong>Сортировка:</strong>
    <a href="/posts?sort=title&dir={if $sortBy === 'title' && $sortDir === 'asc'}desc{else}asc{/if}&page=1"
       class="btn sort-btn {if $sortBy === 'title'}active{/if}">
        По названию {$sortIndicator.title}
    </a>
    <a href="/posts?sort=published_at&dir={if $sortBy === 'published_at' && $sortDir === 'asc'}desc{else}asc{/if}&page=1"
       class="btn sort-btn {if $sortBy === 'published_at'}active{/if}">
        По дате {$sortIndicator.published_at}
    </a>
    <a href="/posts?sort=views&dir={if $sortBy === 'views' && $sortDir === 'asc'}desc{else}asc{/if}&page=1"
       class="btn sort-btn {if $sortBy === 'views'}active{/if}">
        По кол-ву просмотров {$sortIndicator.views}
    </a>
</div>

{if count($posts) > 0}
    <div class="category-posts-list">
        {foreach $posts as $post}
            <div class="category-post-card">

                {* Image on the left *}
                <div class="category-post-image">
                    <img src="/{if $post->image_path}{$post->image_path}{else}images/placeholder.png{/if}" alt="{$post->title}" />
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
                <a href="/posts?sort={$sortBy}&dir={$sortDir}&page=1" title="На первую страницу">« Первая</a>
                <a href="/posts?sort={$sortBy}&dir={$sortDir}&page={$currentPage - 1}">← Предыдущая</a>
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
                    <a href="/posts?sort={$sortBy}&dir={$sortDir}&page={$pageNum}">{$pageNum}</a>
                {/if}
            {/foreach}

            {* Next Page *}
            {if $currentPage < $totalPages}
                <a href="/posts?sort={$sortBy}&dir={$sortDir}&page={$currentPage + 1}">Следующая →</a>
                <a href="/posts?sort={$sortBy}&dir={$sortDir}&page={$totalPages}" title="На последнюю страницу">Последняя »</a>
            {else}
                <span class="disabled">Следующая →</span>
                <span class="disabled">Последняя »</span>
            {/if}
        </div>

        {* Page Info *}
        <div class="posts-page-info">
            Страница {$currentPage} из {$totalPages}
            {* Optional: show items per page info *}
            | Показано {count($posts)} из {$total} статей
        </div>
    {/if}
{else}
    <div class="posts-empty-state">
        <p>Опубликованные статьи не найдены.</p>
        <p style="margin-top: 1rem;">
            <a href="/">← На главную</a>
        </p>
    </div>
{/if}

{/block}

