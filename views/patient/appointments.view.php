<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="page-header">
    <h1 class="page-title">Appointments</h1>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <div class="filter-item">
        <span class="icon">📅</span>
        <select>
            <option>For the entire period</option>
            <option>This week</option>
            <option>This month</option>
            <option>This year</option>
        </select>
    </div>
    <div class="filter-item">
        <span class="icon">✓</span>
        <select>
            <option>All statuses</option>
            <option>Completed</option>
            <option>Scheduled</option>
            <option>Canceled</option>
        </select>
    </div>
    <div class="filter-item">
        <span class="icon">👤</span>
        <select>
            <option>Only mine</option>
            <option>All</option>
        </select>
    </div>
    <div class="filter-item">
        <span class="icon">📋</span>
        <select>
            <option>New ones on top</option>
            <option>Oldest first</option>
        </select>
    </div>
    <button class="btn-reset">
        <span class="icon">🔄</span>
        <span>Reset</span>
    </button>
</div>

<?php if ($error): ?>
    <div class="alert alert-error">
        <span>⚠️</span>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
<?php endif; ?>

<!-- Appointments List -->
<?php if (empty($appointments) && empty($upcoming_appointments) && empty($past_appointments)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">📅</div>
        <div class="empty-state-text">No appointments found.</div>
        <a href="/patient/appointments/create" class="empty-state-link">Book your first appointment now!</a>
    </div>
<?php else: ?>
    <!-- Upcoming Appointments -->
    <?php if (!empty($upcoming_appointments)): ?>
        <?php foreach ($upcoming_appointments as $apt): ?>
            <?php
            $statusName = strtolower($apt['status_name'] ?? 'scheduled');
            $isCompleted = $statusName === 'completed';
            $isCanceled = $statusName === 'canceled' || $statusName === 'cancelled';
            $statusClass = $isCompleted ? 'badge-success' : ($isCanceled ? 'badge-error' : 'badge-warning');
            
            $docInitial = strtoupper(substr($apt['doc_first_name'] ?? 'D', 0, 1));
            $docName = 'Dr. ' . htmlspecialchars(($apt['doc_first_name'] ?? '') . ' ' . ($apt['doc_last_name'] ?? ''));
            $specName = htmlspecialchars($apt['spec_name'] ?? 'General Practice');
            ?>
            <div class="reception-card">
                <div class="reception-header">
                    <div class="reception-doctor">
                        <div class="doctor-avatar"><?= $docInitial ?></div>
                        <div class="doctor-info">
                            <h3><?= $docName ?></h3>
                            <p><?= $specName ?></p>
                        </div>
                    </div>
                    <button class="btn-register">REGISTER NOW</button>
                </div>
                
                <div class="reception-details">
                    <div class="detail-item">
                        <span class="icon">✓</span>
                        <div>
                            <div class="label">Status</div>
                            <div class="value">
                                <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($apt['status_name'] ?? 'Scheduled') ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="icon">📅</span>
                        <div>
                            <div class="label">Date</div>
                            <div class="value"><?= date('l, M j, Y', strtotime($apt['appointment_date'])) ?></div>
                        </div>
                    </div>
                    
                    <?php if ($apt['appointment_time']): ?>
                    <div class="detail-item">
                        <span class="icon">🕐</span>
                        <div>
                            <div class="label">Time</div>
                            <div class="value"><?= date('g:i A', strtotime($apt['appointment_time'])) ?> — <?= date('g:i A', strtotime($apt['appointment_time'] . ' +' . ($apt['appointment_duration'] ?? 30) . ' minutes')) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($apt['address']) && $apt['address']): ?>
                    <div class="detail-item">
                        <span class="icon">📍</span>
                        <div>
                            <div class="label">Address</div>
                            <div class="value"><?= htmlspecialchars($apt['address']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($apt['office']) && $apt['office']): ?>
                    <div class="detail-item">
                        <span class="icon">🏢</span>
                        <div>
                            <div class="label">Office</div>
                            <div class="value"><?= htmlspecialchars($apt['office']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-item">
                        <span class="icon">📄</span>
                        <div>
                            <div class="label">Description</div>
                            <div class="value">
                                <?php if ($apt['appointment_notes']): ?>
                                    <a href="#" style="color: var(--primary-blue); text-decoration: none;">Visit Summary 👁️</a>
                                <?php else: ?>
                                    Not found.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="icon">💊</span>
                        <div>
                            <div class="label">Prescription</div>
                            <div class="value">
                                <?php if (isset($apt['prescription']) && $apt['prescription']): ?>
                                    <a href="#" style="color: var(--primary-blue); text-decoration: none;"><?= htmlspecialchars($apt['prescription']) ?></a>
                                <?php else: ?>
                                    Not found.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Past Appointments -->
    <?php if (!empty($past_appointments)): ?>
        <?php foreach ($past_appointments as $apt): ?>
            <?php
            $statusName = strtolower($apt['status_name'] ?? 'completed');
            $isCompleted = $statusName === 'completed';
            $isCanceled = $statusName === 'canceled' || $statusName === 'cancelled';
            $statusClass = $isCompleted ? 'badge-success' : ($isCanceled ? 'badge-error' : 'badge-warning');
            
            $docInitial = strtoupper(substr($apt['doc_first_name'] ?? 'D', 0, 1));
            $docName = 'Dr. ' . htmlspecialchars(($apt['doc_first_name'] ?? '') . ' ' . ($apt['doc_last_name'] ?? ''));
            $specName = htmlspecialchars($apt['spec_name'] ?? 'General Practice');
            ?>
            <div class="reception-card">
                <div class="reception-header">
                    <div class="reception-doctor">
                        <div class="doctor-avatar"><?= $docInitial ?></div>
                        <div class="doctor-info">
                            <h3><?= $docName ?></h3>
                            <p><?= $specName ?></p>
                        </div>
                    </div>
                    <button class="btn-register">REGISTER NOW</button>
                </div>
                
                <div class="reception-details">
                    <div class="detail-item">
                        <span class="icon">✓</span>
                        <div>
                            <div class="label">Status</div>
                            <div class="value">
                                <span class="badge <?= $statusClass ?>"><?= htmlspecialchars($apt['status_name'] ?? 'Completed') ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="icon">📅</span>
                        <div>
                            <div class="label">Date</div>
                            <div class="value"><?= date('l, M j, Y', strtotime($apt['appointment_date'])) ?></div>
                        </div>
                    </div>
                    
                    <?php if ($apt['appointment_time']): ?>
                    <div class="detail-item">
                        <span class="icon">🕐</span>
                        <div>
                            <div class="label">Time</div>
                            <div class="value"><?= date('g:i A', strtotime($apt['appointment_time'])) ?> — <?= date('g:i A', strtotime($apt['appointment_time'] . ' +' . ($apt['appointment_duration'] ?? 30) . ' minutes')) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($apt['address']) && $apt['address']): ?>
                    <div class="detail-item">
                        <span class="icon">📍</span>
                        <div>
                            <div class="label">Address</div>
                            <div class="value"><?= htmlspecialchars($apt['address']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($apt['office']) && $apt['office']): ?>
                    <div class="detail-item">
                        <span class="icon">🏢</span>
                        <div>
                            <div class="label">Office</div>
                            <div class="value"><?= htmlspecialchars($apt['office']) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="detail-item">
                        <span class="icon">📄</span>
                        <div>
                            <div class="label">Description</div>
                            <div class="value">
                                <?php if ($apt['appointment_notes']): ?>
                                    <a href="#" style="color: var(--primary-blue); text-decoration: none;">Visit Summary 👁️</a>
                                <?php else: ?>
                                    Not found.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <span class="icon">💊</span>
                        <div>
                            <div class="label">Prescription</div>
                            <div class="value">
                                <?php if (isset($apt['prescription']) && $apt['prescription']): ?>
                                    <a href="#" style="color: var(--primary-blue); text-decoration: none;"><?= htmlspecialchars($apt['prescription']) ?></a>
                                <?php else: ?>
                                    Not found.
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
