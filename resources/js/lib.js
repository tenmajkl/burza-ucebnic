// Collection of functions that need to be callen from several places

export async function getNotificationCount() {
    let res = await fetch('/api/notifications/unread');
    let data = await res.json();

    return data.data;
}
