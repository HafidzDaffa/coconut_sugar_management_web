<script setup>
import { computed } from 'vue';
import { usePage, router, Link, Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'Dashboard',
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'User', role: 'superadmin' });
const currentUrl = computed(() => page.url);

const logout = () => {
    router.post('/logout');
};
</script>

<template>
    <Head :title="title" />

    <div class="app-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="sidebar-logo">
                    <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16 2C16 2 6 10 6 18C6 22.4 10.5 26 16 26C21.5 26 26 22.4 26 18C26 10 16 2 16 2Z" fill="white" fill-opacity="0.9"/>
                        <circle cx="16" cy="18" r="4" fill="#1E3A5F"/>
                    </svg>
                </div>
                <div>
                    <p class="sidebar-brand-name">CocoSugar</p>
                    <p class="sidebar-brand-sub">Management</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <Link
                    href="/dashboard"
                    class="nav-item"
                    :class="{ 'nav-item--active': currentUrl.startsWith('/dashboard') }"
                >
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Dashboard
                </Link>

                <Link
                    href="/branches"
                    class="nav-item"
                    :class="{ 'nav-item--active': currentUrl.startsWith('/branches') }"
                >
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1a1 1 0 011-1h1a1 1 0 011 1v1a1 1 0 01-1 1H8a1 1 0 01-1-1V5zm1 4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1v-1a1 1 0 00-1-1H8zm3-4a1 1 0 011-1h1a1 1 0 011 1v1a1 1 0 01-1 1h-1a1 1 0 01-1-1V5zm1 4a1 1 0 00-1 1v1a1 1 0 001 1h1a1 1 0 001-1v-1a1 1 0 00-1-1h-1z" clip-rule="evenodd" />
                    </svg>
                    Branches
                </Link>

                <Link
                    href="/users"
                    class="nav-item"
                    :class="{ 'nav-item--active': currentUrl.startsWith('/users') }"
                >
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    Users
                </Link>
            </nav>

            <button @click="logout" class="logout-btn">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                </svg>
                Sign Out
            </button>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div>
                    <h1 class="page-title">{{ title }}</h1>
                    <p class="page-subtitle">Integrated Coconut Sugar Operations & Management</p>
                </div>
                <div class="user-badge">
                    <div class="user-avatar">{{ user.name.charAt(0) }}</div>
                    <div>
                        <p class="user-name">{{ user.name }}</p>
                        <p class="user-role">{{ user.role }}</p>
                    </div>
                </div>
            </header>

            <!-- Slot -->
            <div class="page-body">
                <slot />
            </div>
        </main>
    </div>
</template>

<style scoped>
.app-layout {
    display: flex;
    min-height: 100vh;
    background: #F8FAFC;
    font-family: 'Instrument Sans', sans-serif;
}

/* Sidebar */
.sidebar {
    width: 240px;
    background: linear-gradient(180deg, #1E3A5F 0%, #1E40AF 100%);
    display: flex;
    flex-direction: column;
    padding: 1.5rem 1rem;
    flex-shrink: 0;
    position: sticky;
    top: 0;
    height: 100vh;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0 0.5rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 1.5rem;
}

.sidebar-logo {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.15);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 6px;
    flex-shrink: 0;
}

.sidebar-logo svg { width: 100%; height: 100%; }

.sidebar-brand-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: white;
    line-height: 1.1;
}

.sidebar-brand-sub {
    font-size: 0.7rem;
    color: rgba(255,255,255,0.5);
}

.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    flex: 1;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.875rem;
    border-radius: 10px;
    color: rgba(255,255,255,0.65);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
}

.nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

.nav-item:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.nav-item--active {
    background: rgba(255,255,255,0.18);
    color: white;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.logout-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.65rem 0.875rem;
    border-radius: 10px;
    background: transparent;
    border: 1px solid rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.65);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    width: 100%;
    font-family: inherit;
}

.logout-btn svg { width: 18px; height: 18px; }

.logout-btn:hover {
    background: rgba(244, 63, 94, 0.2);
    border-color: rgba(244, 63, 94, 0.4);
    color: #FCA5A5;
}

/* Main Content */
.main-content {
    flex: 1;
    padding: 2rem;
    overflow-y: auto;
}

.topbar {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0F172A;
    letter-spacing: -0.02em;
}

.page-subtitle {
    font-size: 0.875rem;
    color: #94A3B8;
    margin-top: 0.2rem;
}

.user-badge {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 0.5rem 1rem 0.5rem 0.5rem;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #1E40AF, #3B82F6);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 0.9rem;
}

.user-name {
    font-size: 0.85rem;
    font-weight: 600;
    color: #0F172A;
}

.user-role {
    font-size: 0.75rem;
    color: #3B82F6;
    text-transform: capitalize;
}

.page-body {
    width: 100%;
}
</style>
