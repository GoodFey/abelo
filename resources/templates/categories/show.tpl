{* Category Page Template *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / <a href="/categories">Категории</a> / {$category->name}
</div>

{* Category Header *}
<h1>{$category->name}</h1>
<p style="color: #64748b; font-size: 1.1rem;">{$category->description|default:'Статьи категории'}</p>

<hr style="margin: 2rem 0; border: none; border-top: 1px solid #e2e8f0;">

{* Posts in Category *}
<section>
    {if count($posts) > 0}
        <h2>Статьи в этой категории ({count($posts)})</h2>

        <div class="posts-list">
            {foreach $posts as $post}
                <div class="post-card">
                    <h3>
                        <a href="/posts/{$post->slug}">{$post->title}</a>
                    </h3>

                    <div class="post-meta">
                        <span>📅 {$post->published_at|date_format:'%d.%m.%Y'}</span>
                        {if $post->author_id}
                            <span> | ✍️ Автор ID: {$post->author_id}</span>
                        {/if}
                    </div>

                    <p class="post-excerpt">{$post->excerpt|default:$post->content|substr:0:200}...</p>

                    <div class="post-categories">
                        {* Get categories for this post *}
                        {foreach $post->getCategories() as $cat}
                            <a href="/categories/{$cat->slug}" class="badge">{$cat->name}</a>
                        {/foreach}
                    </div>

                    <div style="margin-top: 1rem;">
                        <a href="/posts/{$post->slug}" class="btn">Читать статью →</a>
                    </div>
                </div>
            {/foreach}
        </div>
    {else}
        <div style="background-color: #fff3cd; padding: 2rem; border-radius: 0.5rem; text-align: center;">
            <p>В этой категории пока нет статей.</p>
            <p style="margin-top: 1rem;">
                <a href="/categories">← Вернуться к категориям</a>
            </p>
        </div>
    {/if}
</section>

{* Sidebar with other categories *}
<aside style="margin-top: 3rem; display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div></div>
    <div class="sidebar">
        <h3>Другие категории</h3>
        <ul>
            {* This would require fetching all categories in controller *}
            <li><a href="/categories">← Все категории</a></li>
        </ul>
    </div>
</aside>
