{* Single Post Page Template *}

{* Breadcrumb *}
<div class="breadcrumb">
    <a href="/">Главная</a> / <a href="/posts">Статьи</a> / {$post->title}
</div>

{* Post Header *}
<article>
    <h1>{$post->title}</h1>

    <div class="post-meta" style="margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #e2e8f0;">
        <span>📅 Опубликовано: {$post->published_at|date_format:'%d %B %Y'}</span>
        <span> | ⏱️ Обновлено: {$post->updated_at|date_format:'%d.%m.%Y'}</span>
        <span> | 👁️ Просмотров: {$post->views}</span>
        {if $post->author_id}
            <span> | ✍️ Автор ID: {$post->author_id}</span>
        {/if}
    </div>

    {* Categories *}
    {if count($categories) > 0}
        <div class="post-categories" style="margin-bottom: 2rem;">
            <strong>Категории:</strong>
            {foreach $categories as $category}
                <a href="/categories/{$category->slug}" class="badge">{$category->name}</a>
            {/foreach}
        </div>
    {/if}

    {* Post Content *}
    <div class="content-body">
        {$post->content}
    </div>

    {* Post Footer *}
    <hr style="margin: 2rem 0; border: none; border-top: 1px solid #e2e8f0;">

    <div style="background-color: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
        <h3>Об этой статье</h3>
        <p><strong>Заголовок:</strong> {$post->title}</p>
        <p><strong>URL:</strong> /posts/{$post->slug}</p>
        {if $post->excerpt}
            <p><strong>Описание:</strong> {$post->excerpt}</p>
        {/if}
    </div>
</article>

{* Navigation *}
<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
    <div>
        <a href="/posts" class="btn" style="background-color: #64748b;">← Все статьи</a>
    </div>
    <div>
        <a href="/" class="btn" style="background-color: #64748b;">На главную →</a>
    </div>
</div>

{* Similar Posts Section *}
{if count($similarPosts) > 0}
    <section style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
        <h2>📚 Похожие статьи</h2>
        <p style="color: #64748b; margin-bottom: 1.5rem;">
            Статьи из {if count($categories) > 0}категории {$categories[0]->name}{else}похожих категорий{/if}
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            {foreach $similarPosts as $similar}
                <div class="post-card" style="height: 100%;">
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
    <section style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e2e8f0;">
        <h2>⭐ Рекомендуемые статьи</h2>
        <p style="color: #64748b; margin-bottom: 1.5rem;">
            На основе ваших интересов
        </p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            {foreach $recommendedPosts as $recommended}
                <div class="post-card" style="height: 100%; border-left: 3px solid #f59e0b;">
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
