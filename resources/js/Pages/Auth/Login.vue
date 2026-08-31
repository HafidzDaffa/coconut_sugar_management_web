<script setup>
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Sign In" />

    <div class="auth-wrapper">
        <!-- Left Panel: Brand -->
        <div class="brand-panel">
            <div class="brand-content">
                <div class="brand-icon">
                    <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="32" fill="rgba(255,255,255,0.1)" />
                        <path d="M32 12C32 12 18 22 18 34C18 41.7 24.3 48 32 48C39.7 48 46 41.7 46 34C46 22 32 12 32 12Z" fill="white" fill-opacity="0.9"/>
                        <path d="M32 48V54" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <path d="M26 52H38" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <circle cx="32" cy="33" r="6" fill="#1E3A5F"/>
                        <circle cx="32" cy="33" r="3" fill="white" fill-opacity="0.6"/>
                    </svg>
                </div>
                <h1 class="brand-title">Coconut Sugar<br>Management</h1>
                <p class="brand-tagline">Integrated coconut sugar production & operations platform</p>

                <div class="brand-stats">
                    <div class="stat">
                        <span class="stat-number">100%</span>
                        <span class="stat-label">Integrated</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <span class="stat-number">Real-time</span>
                        <span class="stat-label">Monitoring</span>
                    </div>
                    <div class="stat-divider"></div>
                    <div class="stat">
                        <span class="stat-number">Secure</span>
                        <span class="stat-label">& Protected</span>
                    </div>
                </div>
            </div>

            <!-- Decorative circles -->
            <div class="deco-circle deco-circle--1"></div>
            <div class="deco-circle deco-circle--2"></div>
            <div class="deco-circle deco-circle--3"></div>
        </div>

        <!-- Right Panel: Login Form -->
        <div class="form-panel">
            <div class="form-container">
                <div class="form-header">
                    <h2 class="form-title">Welcome Back</h2>
                    <p class="form-subtitle">Please sign in to your account to continue</p>
                </div>

                <form @submit.prevent="submit" class="login-form">
                    <!-- Email -->
                    <div class="field-group">
                        <label for="email" class="field-label">Email Address</label>
                        <div class="field-wrapper" :class="{ 'field-wrapper--error': form.errors.email }">
                            <svg class="field-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="superadmin@coconutsugar.com"
                                autocomplete="username"
                                class="field-input"
                                autofocus
                            />
                        </div>
                        <p v-if="form.errors.email" class="field-error">{{ form.errors.email }}</p>
                    </div>

                    <!-- Password -->
                    <div class="field-group">
                        <label for="password" class="field-label">Password</label>
                        <div class="field-wrapper" :class="{ 'field-wrapper--error': form.errors.password }">
                            <svg class="field-icon" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                class="field-input"
                            />
                        </div>
                        <p v-if="form.errors.password" class="field-error">{{ form.errors.password }}</p>
                    </div>

                    <!-- Remember me -->
                    <div class="remember-row">
                        <label class="remember-label">
                            <input v-model="form.remember" type="checkbox" class="remember-checkbox" />
                            <span>Remember me</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button
                        type="submit"
                        class="submit-btn"
                        :class="{ 'submit-btn--loading': form.processing }"
                        :disabled="form.processing"
                    >
                        <svg v-if="form.processing" class="spin" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="30" stroke-dashoffset="10"/>
                        </svg>
                        <span>{{ form.processing ? 'Signing in...' : 'Sign in to Dashboard' }}</span>
                        <svg v-if="!form.processing" viewBox="0 0 20 20" fill="currentColor" class="btn-arrow">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </form>

                <p class="form-footer">
                    Coconut Sugar Management System &copy; {{ new Date().getFullYear() }}
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Layout */
.auth-wrapper {
    display: flex;
    min-height: 100vh;
    font-family: 'Instrument Sans', sans-serif;
}

/* ─── Brand Panel ─── */
.brand-panel {
    position: relative;
    width: 45%;
    background: linear-gradient(145deg, #1E3A5F 0%, #1E40AF 60%, #2563EB 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 3rem;
}

.brand-content {
    position: relative;
    z-index: 2;
    text-align: center;
    color: white;
}

.brand-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
}

.brand-icon svg {
    width: 100%;
    height: 100%;
}

.brand-title {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
}

.brand-tagline {
    font-size: 0.95rem;
    color: rgba(255, 255, 255, 0.7);
    max-width: 280px;
    margin: 0 auto 2.5rem;
    line-height: 1.6;
}

.brand-stats {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    justify-content: center;
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    backdrop-filter: blur(8px);
}

.stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

.stat-number {
    font-size: 0.95rem;
    font-weight: 700;
    color: white;
}

.stat-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.55);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-divider {
    width: 1px;
    height: 32px;
    background: rgba(255, 255, 255, 0.2);
}

/* Decorative circles */
.deco-circle {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.08);
}
.deco-circle--1 { width: 400px; height: 400px; top: -100px; right: -150px; }
.deco-circle--2 { width: 250px; height: 250px; bottom: -60px; left: -80px; }
.deco-circle--3 { width: 120px; height: 120px; top: 50%; right: 40px; transform: translateY(-50%); background: rgba(255,255,255,0.04); }

/* ─── Form Panel ─── */
.form-panel {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #F8FAFC;
    padding: 3rem;
}

.form-container {
    width: 100%;
    max-width: 400px;
}

.form-header {
    margin-bottom: 2rem;
}

.form-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #0F172A;
    margin-bottom: 0.4rem;
    letter-spacing: -0.02em;
}

.form-subtitle {
    font-size: 0.9rem;
    color: #94A3B8;
}

/* Form fields */
.login-form {
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
}

.field-group {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.field-label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #0F172A;
}

.field-wrapper {
    display: flex;
    align-items: center;
    background: white;
    border: 1.5px solid #E2E8F0;
    border-radius: 10px;
    transition: border-color 0.2s, box-shadow 0.2s;
    overflow: hidden;
}

.field-wrapper:focus-within {
    border-color: #3B82F6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
}

.field-wrapper--error {
    border-color: #F43F5E;
}

.field-wrapper--error:focus-within {
    box-shadow: 0 0 0 3px rgba(244, 63, 94, 0.12);
}

.field-icon {
    width: 16px;
    height: 16px;
    color: #94A3B8;
    flex-shrink: 0;
    margin-left: 0.875rem;
}

.field-input {
    flex: 1;
    padding: 0.75rem 0.875rem;
    font-size: 0.9rem;
    color: #0F172A;
    background: transparent;
    border: none;
    outline: none;
    font-family: inherit;
}

.field-input::placeholder { color: #CBD5E1; }

.field-error {
    font-size: 0.8rem;
    color: #F43F5E;
}

/* Remember */
.remember-row {
    display: flex;
    align-items: center;
}

.remember-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: #64748B;
    cursor: pointer;
}

.remember-checkbox {
    width: 16px;
    height: 16px;
    accent-color: #1E40AF;
    cursor: pointer;
}

/* Submit button */
.submit-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    width: 100%;
    padding: 0.875rem 1.5rem;
    background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%);
    color: white;
    font-size: 0.95rem;
    font-weight: 600;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
    box-shadow: 0 4px 14px rgba(30, 64, 175, 0.35);
    margin-top: 0.25rem;
    font-family: inherit;
}

.submit-btn:hover:not(:disabled) {
    opacity: 0.92;
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(30, 64, 175, 0.45);
}

.submit-btn:active:not(:disabled) {
    transform: translateY(0);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
}

.btn-arrow {
    width: 16px;
    height: 16px;
}

.spin {
    width: 16px;
    height: 16px;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Footer */
.form-footer {
    text-align: center;
    font-size: 0.78rem;
    color: #CBD5E1;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .auth-wrapper { flex-direction: column; }
    .brand-panel { width: 100%; padding: 2.5rem 1.5rem; }
    .brand-title { font-size: 1.5rem; }
    .form-panel { padding: 2rem 1.5rem; }
}
</style>
