<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'User', role: 'superadmin' });
</script>

<template>
    <AppLayout title="Dashboard">
        <!-- Stats Cards -->
        <section class="stats-grid">
            <div class="stat-card stat-card--blue">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Total Production</p>
                    <p class="stat-value">0 kg</p>
                    <p class="stat-change">This month</p>
                </div>
            </div>
            <div class="stat-card stat-card--emerald">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.077 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.077-2.354-1.253V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Total Revenue</p>
                    <p class="stat-value">$ 0</p>
                    <p class="stat-change">This month</p>
                </div>
            </div>
            <div class="stat-card stat-card--amber">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Active Workers</p>
                    <p class="stat-value">0</p>
                    <p class="stat-change">Active</p>
                </div>
            </div>
            <div class="stat-card stat-card--rose">
                <div class="stat-card-icon">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="stat-label">Pending Reports</p>
                    <p class="stat-value">0</p>
                    <p class="stat-change">Requires review</p>
                </div>
            </div>
        </section>

        <!-- Welcome card -->
        <div class="welcome-card">
            <div class="welcome-text">
                <h2>System Ready 🎉</h2>
                <p>Welcome to the Coconut Sugar Management platform. Manage your operational facilities via the <strong>Branches</strong> menu. You are signed in as <strong>{{ user.role }}</strong>.</p>
            </div>
            <div class="welcome-badge">
                <span>{{ user.role }}</span>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
* { box-sizing: border-box; margin: 0; padding: 0; }

.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
    background: #F8FAFC;
    font-family: 'Instrument Sans', sans-serif;
}

/* ─── Sidebar ─── */
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
    background: rgba(255,255,255,0.15);
    color: white;
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

/* ─── Main Content ─── */
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

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    border-radius: 14px;
    padding: 1.25rem;
    border: 1px solid #E2E8F0;
    transition: transform 0.15s, box-shadow 0.15s;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.07);
}

.stat-card-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-card-icon svg { width: 22px; height: 22px; }

.stat-card--blue .stat-card-icon { background: #EFF6FF; color: #1E40AF; }
.stat-card--emerald .stat-card-icon { background: #ECFDF5; color: #10B981; }
.stat-card--amber .stat-card-icon { background: #FFFBEB; color: #F59E0B; }
.stat-card--rose .stat-card-icon { background: #FFF1F2; color: #F43F5E; }

.stat-label {
    font-size: 0.75rem;
    color: #94A3B8;
    margin-bottom: 0.2rem;
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0F172A;
    letter-spacing: -0.02em;
}

.stat-change {
    font-size: 0.7rem;
    color: #CBD5E1;
    margin-top: 0.1rem;
}

/* Welcome card */
.welcome-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
    border-radius: 16px;
    padding: 1.75rem 2rem;
    color: white;
}

.welcome-text h2 {
    font-size: 1.15rem;
    font-weight: 700;
    margin-bottom: 0.4rem;
}

.welcome-text p {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.75);
    max-width: 500px;
    line-height: 1.6;
}

.welcome-badge span {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    padding: 0.4rem 1rem;
    border-radius: 100px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>
