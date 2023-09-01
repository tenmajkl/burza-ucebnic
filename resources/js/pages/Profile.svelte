<script>
    import Text from "../components/Text.svelte";

    function logout() {
        fetch('/api/logout', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json',
            },
            body: '{}'
        }).then(() => window.location = '/')
    }
</script>

<div class="text-2xl font-bold">
    <Text text="profile-title" />
</div>

<button class="button" on:click={logout}><Text text="profile-logout" /></button>
