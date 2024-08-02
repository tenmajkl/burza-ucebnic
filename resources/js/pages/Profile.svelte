<script>
    import Text from "../components/Text.svelte";
    
    let old_password = '';
    let new_password = '';
    let error = null;
    let success = false;

    function logout() {
        fetch('/api/logout', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        }).then(() => window.location = '/')
    }

    function changePassword()
    {
        fetch('/api/change-password', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                'old_password': old_password,
                'password': new_password
            })
        })
        .then((res) => res.json())
        .then((res) => {
            if (res.status != 200) {
                error = res.message;
                success = false;
            } else {
                success = true;
                error = null;
            }
        })
    }
</script>

<div class="text-2xl font-bold">
    <Text text="profile-title" />
</div>

<div class="block">
    <button class="button" on:click={logout}><Text text="profile-logout" /></button>
    
    <hr class="my-2 border-t-2 rounded-sm border-primary">

    <div class="block gap-1">
        <div class="text-xl font-bold"><Text text="profile-change-password" /></div>
        {#if error}
            <div class="alert">
                {error}
            </div>            
        {/if}

        {#if success}
            <div class="alert-success">
                <Text text="profile-password-changed"></Text>
            </div>            
        {/if}
        <div class="flex flex-col sm:w-1/3 md:w-1/2 xl:w-1/4">
            <Text text="profile-old-password" />
            <input class="input" type="password" bind:value={old_password}></div>
        <div>
        <div class="flex flex-col sm:w-1/3 md:w-1/2 xl:w-1/4">
            <Text text="profile-new-password" />
            <input class="input" type="password" bind:value={new_password}></div>
        </div>
        <button on:click={changePassword} class="button mt-2"><Text text="profile-change-password" /></button>
    </div>
</div>
