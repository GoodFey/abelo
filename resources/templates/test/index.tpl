{extends file="layout.tpl"}

{block name="content"}
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>🔧 Database Tests</h1>
            <p class="lead">Проверка подключения и работоспособности БД</p>
            <hr>

            {foreach from=$tests item=test key=key}
                <div class="card mb-3 {if $test.status === 'OK'}border-success{elseif $test.status === 'WARNING'}border-warning{elseif $test.status === 'ERROR'}border-danger{/if}">
                    <div class="card-header bg-{if $test.status === 'OK'}success{elseif $test.status === 'WARNING'}warning{elseif $test.status === 'ERROR'}danger{/if} text-white">
                        <h5 class="mb-0">
                            {if $test.status === 'OK'}
                                ✅
                            {elseif $test.status === 'WARNING'}
                                ⚠️
                            {elseif $test.status === 'ERROR'}
                                ❌
                            {/if}
                            {$test.name}
                            <span class="badge badge-{if $test.status === 'OK'}success{elseif $test.status === 'WARNING'}warning{elseif $test.status === 'ERROR'}danger{/if}">{$test.status}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{$test.message}</p>

                        {if isset($test.count)}
                            <small class="text-muted">Count: <strong>{$test.count}</strong></small>
                        {/if}

                        {if isset($test.title)}
                            <ul class="small text-muted">
                                <li>Title: {$test.title}</li>
                                <li>Slug: {$test.slug}</li>
                            </ul>
                        {/if}

                        {if isset($test.name_cat)}
                            <ul class="small text-muted">
                                <li>Name: {$test.name_cat}</li>
                                <li>Slug: {$test.slug_cat}</li>
                            </ul>
                        {/if}

                        {if isset($test.id)}
                            <small class="text-muted">ID: <strong>{$test.id}</strong></small>
                        {/if}
                    </div>
                </div>
            {/foreach}

            <div class="mt-4">
                <h5>API Endpoint</h5>
                <p>JSON responses available at:</p>
                <code class="bg-light p-2 d-block">/api/test</code>
            </div>

            <div class="mt-4">
                <a href="/" class="btn btn-primary">← Back to Home</a>
            </div>
        </div>
    </div>
</div>
{/block}

