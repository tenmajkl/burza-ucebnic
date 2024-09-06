<script>
    import PopUp from "../components/PopUp.svelte";
    import Text from "../components/Text.svelte";
    import { _text } from "../main";
    
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

    function changePassword() {
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


    let password;
    let year;
    let errorPopUp = null;
    let yearPopup = false;
    let deletePopup = false;
    let name = '';
    let rating = 0;
    let user_year = '';

    function getInfo() {
        fetch('/api/account/info').then((res) => res.json()).then((res) => {
            name = res.data.name;
            rating = res.data.rating;
            user_year = res.data.year;
        });
    }

    getInfo();

    async function getYears() {
        let res = await fetch('/api/account/years');
        res = await res.json();
        year = Object.values(res.data)[0].id;
        return res.data;
    }

    function changeYear() {
        if (password.length < 8) {
            return;
        }

        fetch('/api/account/changeYear/', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                password: password,
                year: year,
            }),
        }).then((res) => res.json())
            .then((res) => {
                if (res.code !== 200) {
                    errorPopUp = res.message;
                    return;
                }

                yearPopup = false;
            })
    }

    function deleteAccount() {
        fetch('/api/account/delete/', {
            method: 'post',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                password: password,
            }),
        }).then((res) => res.json())
            .then((res) => {
                if (res.code === 200) {
                    window.location = '/';
                    return;
                }

                errorPopUp = res.message;
        })       
    }

    function clear() {
        errorPopUp = null;
        password = null;
    }

</script>

{#if yearPopup}
    <PopUp bind:state={yearPopup} onClose={clear}>
        <div class="card w-9/12 sm:w-1/3">
            {#if errorPopUp} 
                <div class="alert">{errorPopUp}</div>
            {/if}
            <div class="flex flex-col gap-3">
                <Text text="profile-pick-year"></Text>
                    {#await getYears()}
                    {:then years}
                        <select id="year" name="year" class="input" bind:value={year} required>
                            {#each Object.values(years) as year}
                                <option value="{year.id}">{year.name}</option>
                            {/each}

                        </select>
                    {/await}

                <Text text="profile-change-year-u-sure"></Text>
                    <input type="password" bind:value={password} class="input" placeholder={_text('profile-password')} on:keydown={(e) => { if (e.key =='Enter') { changeYear() } }}>
                <button class="button-red danger" on:click={changeYear}>
                    <Text text="profile-change-year"></Text>
                </button>
            </div>
        </div>
    </PopUp>
{/if}

{#if deletePopup}
    <PopUp bind:state={deletePopup} onClose={clear}>
        {#if errorPopUp} 
            <div class="alert">{errorPopUp}</div>
        {/if}
        <div class="flex flex-col gap-3">
            <Text text="profile-delete-u-sure"></Text>
            <input type="password" bind:value={password} class="input" placeholder={_text('profile-password')} on:keydown={(e) => { if (e.key =='Enter') { deleteAccount() } }}>
            <button class="button-red danger" on:click={deleteAccount}>
                <Text text="profile-delete"></Text>
            </button>
        </div>
    </PopUp>
{/if}

<div class="text-2xl font-bold">
    <Text text="profile-title" />
</div>

@{name} (<span class="text-{rating > 0 ? 'blue' : 'red'}" title={_text('rating', name)}>{rating}</span>) {user_year}

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
            <input class="input" type="password" bind:value={new_password} on:keydown={(e) => { if (e.key == 'Enter') { changePassword() } }}></div>
        </div>
        <button on:click={changePassword} class="button mt-2"><Text text="profile-change-password" /></button>
            
    </div>

    <hr class="my-2 border-t-2 rounded-sm border-primary">
    
    <div class="text-2xl font-bold text-red">
        <Text text="profile-dangerous-zone"></Text>
    </div>

    <button class="button-red danger" on:click={() => yearPopup = true}>
        <Text text="profile-change-year"></Text>
    </button>
    <button class="button-red danger" on:click={() => deletePopup = true}>
        <Text text="profile-delete"></Text>
    </button>
</div>
