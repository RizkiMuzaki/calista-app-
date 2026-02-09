@extends('layouts.app')

@section('content')
<div class="payment-page">
    <div class="payment-wrapper">
        <!-- Progress Steps -->
        <div class="payment-progress">
            <div class="progress-steps">
                <div class="step active">
                    <div class="step-number">1</div>
                    <span class="step-text">Pilih Paket</span>
                </div>
                <div class="step active">
                    <div class="step-number">2</div>
                    <span class="step-text">Pembayaran</span>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <span class="step-text">Aktif</span>
                </div>
            </div>
        </div>

        <div class="payment-layout">
            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-card">
                    <h3 class="summary-title">Detail Pesanan</h3>
                    
                    <div class="plan-info">
                        <div class="plan-icon">⭐</div>
                        <div class="plan-details">
                            <h4>{{ $paymentData['item_name'] ?? '' }}</h4>
                            <p>{{ $paymentData['plan']->durasi_bulan }} Bulan Akses Premium</p>
                        </div>
                    </div>

                    <div class="price-breakdown">
                        <div class="price-item">
                            <span>Harga Paket</span>
                            <span>Rp {{ number_format($paymentData['amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="price-total">
                            <span>Total</span>
                            <span class="total-amount">Rp {{ number_format($paymentData['amount'] ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if($paymentData['plan']->deskripsi)
                    <div class="benefits-section">
                        <h4>Yang akan kamu dapatkan:</h4>
                        <div class="benefits-list">
                            {!! nl2br(e($paymentData['plan']->deskripsi)) !!}
                        </div>
                    </div>
                    @endif

                    <div class="security-badges">
                        <div class="badge">
                            <span class="badge-icon">🔒</span>
                            <span>Pembayaran Aman</span>
                        </div>
                        <div class="badge">
                            <span class="badge-icon">🛡️</span>
                            <span>Data Terlindungi</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="payment-section">
                <div class="payment-header">
                    <h1>Pilih Metode Pembayaran</h1>
                    <p>Pilih cara bayar yang paling nyaman buat kamu</p>
                </div>

                <div class="methods-container">
                    @foreach($paymentChannels as $channel)
                    <div class="method-card" data-method="{{ $channel->code }}">
                        <div class="method-main">
                            <div class="method-brand">
                                <img src="{{ $channel->icon_url }}" alt="{{ $channel->name }}" class="method-logo"
                                     onerror="this.src='https://via.placeholder.com/32/000000/ffffff?text={{ substr($channel->name, 0, 2) }}'">
                                <div class="method-details">
                                    <h4 class="method-name">{{ $channel->name }}</h4>
                                    <span class="method-category">{{ $channel->group }}</span>
                                </div>
                            </div>
                            
                            <div class="method-meta">
                                @if((int) $channel->fee_customer > 0)
                                <span class="method-fee">+Rp {{ number_format((int) $channel->fee_customer, 0, ',', '.') }}</span>
                                @else
                                @endif
                            </div>
                        </div>
                        
                        <button class="method-select-btn" 
                                data-method="{{ $channel->code }}"
                                data-name="{{ $channel->name }}">
                            Pilih
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M5 12H19M19 12L12 5M19 12L12 19" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>

                <!-- Form tersembunyi -->
                <form id="paymentForm" action="{{ route('plan.payment.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="paymentMethod">
                    <input type="hidden" name="plan_id" value="{{ $paymentData['plan']->id }}">
                </form>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-black: #1a1a1a;
    --secondary-black: #2d2d2d;
    --accent-gray: #4a4a4a;
    --light-gray: #f8f9fa;
    --border-color: #e0e0e0;
    --white: #ffffff;
    --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
    --transition: all 0.3s ease;
    --header-height: 60px; /* Sesuaikan angka ini dengan tinggi header di layout.app */
}

.payment-page {
    min-height: 100vh;
    background: var(--light-gray);
    /* geser seluruh halaman ke bawah agar tidak tertutup header */
    padding: calc(var(--header-height) + 2rem) 0 2rem;
}

.payment-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

/* Progress Steps - PERBAIKAN */
.payment-progress {
    margin-bottom: 3rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 25%;
    right: 25%;
    height: 2px;
    background: var(--border-color);
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    flex: 1;
    
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--white);
    border: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: var(--transition);
    color: var(--accent-gray); /* Tambahkan ini agar angka terlihat */
}

.step.active .step-number {
    background: var(--primary-black);
    border-color: var(--primary-black);
    color: var(--white); /* Pastikan angka berwarna putih saat aktif */
}

.step-text {
    font-size: 0.85rem;
    color: var(--accent-gray);
    font-weight: 500;
}

.step.active .step-text {
    color: var(--primary-black);
}

/* Layout */
.payment-layout {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 2rem;
    align-items: start;
}

/* Order Summary */
.order-summary {
    position: sticky;
    /* pertimbangkan tinggi header saat sticky agar tidak tertutup */
    top: calc(var(--header-height) + 2rem);
}

.summary-card {
    background: var(--white);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.summary-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--primary-black);
    padding-bottom: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.plan-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--light-gray);
    border-radius: 12px;
}

.plan-icon {
    font-size: 2rem;
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: var(--primary-black);
    color: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
}

.plan-details h4 {
    margin: 0 0 0.25rem 0;
    font-size: 1.1rem;
    color: var(--primary-black);
}

.plan-details p {
    margin: 0;
    color: var(--accent-gray);
    font-size: 0.9rem;
}

.price-breakdown {
    margin-bottom: 2rem;
}

.price-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    color: var(--accent-gray);
    border-bottom: 1px solid var(--border-color);
}

.price-total {
    display: flex;
    justify-content: space-between;
    padding: 1rem 0;
    font-weight: 600;
    font-size: 1.1rem;
    color: var(--primary-black);
}

.total-amount {
    color: var(--primary-black);
    font-size: 1.25rem;
}

.benefits-section {
    margin-bottom: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.benefits-section h4 {
    margin-bottom: 1rem;
    color: var(--primary-black);
    font-size: 1rem;
}

.benefits-list {
    color: var(--accent-gray);
    line-height: 1.6;
    font-size: 0.9rem;
}

.security-badges {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--light-gray);
    border-radius: 20px;
    font-size: 0.8rem;
    color: var(--accent-gray);
}

.badge-icon {
    font-size: 0.9rem;
}

/* Payment Section */
.payment-section {
    background: var(--white);
    border-radius: 16px;
    padding: 2.5rem;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.payment-header {
    text-align: center;
    margin-bottom: 2.5rem;
}

.payment-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: var(--primary-black);
}

.payment-header p {
    color: var(--accent-gray);
    font-size: 1rem;
}

.methods-container {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.method-card {
    background: var(--white);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.method-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(26, 26, 26, 0.03), transparent);
    transition: left 0.6s ease;
}

.method-card:hover::before {
    left: 100%;
}

.method-card:hover {
    border-color: var(--primary-black);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.method-card.selected {
    border-color: var(--primary-black);
    background: var(--light-gray);
}

.method-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.method-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.method-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 8px;
    background: var(--light-gray);
    padding: 4px;
}

.method-details {
    flex: 1;
}

.method-name {
    margin: 0 0 0.25rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: var(--primary-black);
}

.method-category {
    font-size: 0.8rem;
    color: var(--accent-gray);
    text-transform: capitalize;
}

.method-meta {
    text-align: right;
}

.method-fee {
    font-size: 0.85rem;
    color: var(--accent-gray);
}

.method-fee.free {
    color: #10b981;
    font-weight: 500;
}

.method-select-btn {
    width: 100%;
    padding: 1rem 1.5rem;
    background: var(--primary-black);
    color: var(--white);
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.method-select-btn:hover {
    background: var(--secondary-black);
    transform: translateY(-1px);
}

.method-select-btn:active {
    transform: translateY(0);
}

.method-select-btn svg {
    transition: transform 0.3s ease;
}

.method-select-btn:hover svg {
    transform: translateX(2px);
}

/* Responsive Design */
@media (max-width: 968px) {
    .payment-layout {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .order-summary {
        position: static;
    }
}

@media (max-width: 768px) {
    .payment-wrapper {
        padding: 0 1rem;
    }
    
    .payment-section {
        padding: 2rem 1.5rem;
    }
    
    .summary-card {
        padding: 1.5rem;
    }
    
    .payment-header h1 {
        font-size: 1.75rem;
    }
    
    .method-main {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .method-meta {
        text-align: left;
        width: 100%;
    }
}

@media (max-width: 480px) {
    .payment-progress {
        margin-bottom: 2rem;
    }
    
    .step-text {
        font-size: 0.75rem;
    }
    
    .plan-info {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .security-badges {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const methodCards = document.querySelectorAll('.method-card');
    const methodButtons = document.querySelectorAll('.method-select-btn');
    const paymentMethodInput = document.getElementById('paymentMethod');
    const paymentForm = document.getElementById('paymentForm');
    
    let selectedMethod = null;
    
    // Handle method selection
    methodButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const method = this.dataset.method;
            const methodName = this.dataset.name;
            
            // Update selected method
            selectedMethod = method;
            paymentMethodInput.value = method;
            
            // Update UI
            methodCards.forEach(card => {
                card.classList.remove('selected');
            });
            this.closest('.method-card').classList.add('selected');
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = 'Memproses...';
            this.disabled = true;
            
            // Submit form after short delay for better UX
            setTimeout(() => {
                paymentForm.submit();
            }, 500);
        });
    });
    
    // Handle card click
    methodCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.classList.contains('method-select-btn')) {
                const btn = this.querySelector('.method-select-btn');
                if (btn) {
                    btn.click();
                }
            }
        });
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && selectedMethod) {
            const selectedCard = document.querySelector('.method-card.selected');
            if (selectedCard) {
                const btn = selectedCard.querySelector('.method-select-btn');
                if (btn) {
                    btn.click();
                }
            }
        }
    });
});
</script>
@endsection