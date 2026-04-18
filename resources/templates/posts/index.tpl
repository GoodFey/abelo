{extends "layout.tpl"}

{block name="content"}

{* Posts Index Page *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / Статьи
</div>

<h1>Все статьи</h1>
<p style="color: #64748b; margin-bottom: 2rem;">Всего опубликовано {$total|default:0} статей</p>

{* Sort Controls *}
<div style="margin-bottom: 2rem; padding: 1rem; background-color: #f8fafc; border-radius: 0.5rem;">
    <strong>Сортировка:</strong>
    <a href="/posts?sort=title&dir={if $sortBy === 'title' && $sortDir === 'asc'}desc{else}asc{/if}&page=1"
       style="margin-left: 1rem;" class="btn" style="background-color: {if $sortBy === 'title'}#3b82f6{else}#64748b{/if}; padding: 0.5rem 1rem;">
        По названию {$sortIndicator.title}
    </a>
    <a href="/posts?sort=published_at&dir={if $sortBy === 'published_at' && $sortDir === 'asc'}desc{else}asc{/if}&page=1"
       style="margin-left: 0.5rem;" class="btn" style="background-color: {if $sortBy === 'published_at'}#3b82f6{else}#64748b{/if}; padding: 0.5rem 1rem;">
        По дате {$sortIndicator.published_at}
    </a>
    <a href="/posts?sort=created_at&dir={if $sortBy === 'created_at' && $sortDir === 'asc'}desc{else}asc{/if}&page=1"
       style="margin-left: 0.5rem;" class="btn" style="background-color: {if $sortBy === 'created_at'}#3b82f6{else}#64748b{/if}; padding: 0.5rem 1rem;">
        По дате создания {$sortIndicator.created_at}
    </a>
</div>

{if count($posts) > 0}
    <div class="posts-list">
        {foreach $posts as $post}
            <div class="post-card">
                <h3>
                    <a href="/posts/{$post->slug}">{$post->title}</a>
                </h3>

                <div class="post-meta">
                    <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                    <span> | 👁️ {$post->views} просмотров</span>
                    {if $post->author_id}
                        <span> | ✍️ Автор ID: {$post->author_id}</span>
                    {/if}
                </div>

                <p class="post-excerpt">{$post->excerpt|default:$post->content|substr:0:250}...</p>

                <a href="/posts/{$post->slug}" class="btn">Читать статью →</a>
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
        <div style="text-align: center; margin-top: 2rem; color: #64748b; font-size: 0.875rem;">
            Страница {$currentPage} из {$totalPages}
            {* Optional: show items per page info *}
            | Показано {count($posts)} из {$total} статей
        </div>
    {/if}
{else}
    <div style="background-color: #fff3cd; padding: 2rem; border-radius: 0.5rem; text-align: center;">
        <p>Опубликованные статьи не найдены.</p>
        <p style="margin-top: 1rem;">
            <a href="/">← На главную</a>
        </p>
    </div>
{/if}

{/block}

