

<?php $__env->startSection('title', 'Lead Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-main-body">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
		<h6 class="fw-semibold mb-0">Lead Management</h6>
		<ul class="d-flex align-items-center gap-2">
			<li class="fw-medium">
				<a href="<?php echo e(route('admin.dashboard')); ?>" class="d-flex align-items-center gap-1 hover-text-primary">
					<iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
					Dashboard
				</a>
			</li>
			<li>-</li>
			<li class="fw-medium text-secondary-light">Lead Management</li>
		</ul>
	</div>

	<?php if(session('success')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?php echo e(session('success')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>
	<?php if(session('error')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?php echo e(session('error')); ?>

			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<?php if($errors->any()): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please review the highlighted fields below.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-lg-3 mb-24">
			<div class="card border-0 h-100">
				<div class="card-body p-16">
					<div class="nav flex-column nav-pills gap-2" id="lead-tabs" role="tablist" aria-orientation="vertical">
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e((request('tab') === 'add' || !request('tab')) ? 'active' : ''); ?>" id="lead-tab-add" data-bs-toggle="pill" data-bs-target="#lead-pane-add" type="button" role="tab" aria-controls="lead-pane-add" aria-selected="<?php echo e((request('tab') === 'add' || !request('tab')) ? 'true' : 'false'); ?>">
							<span>Add Leads</span>
							<iconify-icon icon="solar:add-circle-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'manage' ? 'active' : ''); ?>" id="lead-tab-manage" data-bs-toggle="pill" data-bs-target="#lead-pane-manage" type="button" role="tab" aria-controls="lead-pane-manage" aria-selected="<?php echo e(request('tab') === 'manage' ? 'true' : 'false'); ?>">
							<span>Manage Leads</span>
							<iconify-icon icon="solar:list-check-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'assign' ? 'active' : ''); ?>" id="lead-tab-assign" data-bs-toggle="pill" data-bs-target="#lead-pane-assign" type="button" role="tab" aria-controls="lead-pane-assign" aria-selected="<?php echo e(request('tab') === 'assign' ? 'true' : 'false'); ?>">
							<span>Assign Leads</span>
							<iconify-icon icon="solar:user-check-rounded-outline" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'next-followups' ? 'active' : ''); ?>" id="lead-tab-next" data-bs-toggle="pill" data-bs-target="#lead-pane-next" type="button" role="tab" aria-controls="lead-pane-next" aria-selected="<?php echo e(request('tab') === 'next-followups' ? 'true' : 'false'); ?>">
							<span>Next Followups</span>
							<iconify-icon icon="solar:calendar-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'today-followups' ? 'active' : ''); ?>" id="lead-tab-today" data-bs-toggle="pill" data-bs-target="#lead-pane-today" type="button" role="tab" aria-controls="lead-pane-today" aria-selected="<?php echo e(request('tab') === 'today-followups' ? 'true' : 'false'); ?>">
							<span>Today's Followups</span>
							<iconify-icon icon="solar:alarm-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'analytics' ? 'active' : ''); ?>" id="lead-tab-analytics" data-bs-toggle="pill" data-bs-target="#lead-pane-analytics" type="button" role="tab" aria-controls="lead-pane-analytics" aria-selected="<?php echo e(request('tab') === 'analytics' ? 'true' : 'false'); ?>">
							<span>Lead Analytics</span>
							<iconify-icon icon="solar:chart-square-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'trash' ? 'active' : ''); ?>" id="lead-tab-trash" data-bs-toggle="pill" data-bs-target="#lead-pane-trash" type="button" role="tab" aria-controls="lead-pane-trash" aria-selected="<?php echo e(request('tab') === 'trash' ? 'true' : 'false'); ?>">
							<span>Trash</span>
							<iconify-icon icon="solar:trash-bin-trash-linear" class="text-lg"></iconify-icon>
						</button>
						<button class="btn btn-outline-secondary d-flex justify-content-between align-items-center <?php echo e(request('tab') === 'import-export' ? 'active' : ''); ?>" id="lead-tab-import" data-bs-toggle="pill" data-bs-target="#lead-pane-import" type="button" role="tab" aria-controls="lead-pane-import" aria-selected="<?php echo e(request('tab') === 'import-export' ? 'true' : 'false'); ?>">
							<span>Import / Export</span>
							<iconify-icon icon="solar:cloud-upload-linear" class="text-lg"></iconify-icon>
						</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-9">
			<div class="tab-content" id="lead-tabs-content">
				<!-- Add Leads Tab -->
				<div class="tab-pane fade <?php echo e((request('tab') === 'add' || !request('tab')) ? 'show active' : ''); ?>" id="lead-pane-add" role="tabpanel" aria-labelledby="lead-tab-add">
					<div class="card border-0">
						<div class="card-body p-24">
							<form method="POST" action="<?php echo e(route('admin.leads.store')); ?>" class="row g-4">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="tab" value="add">

								<div class="col-12">
									<h6 class="fw-semibold mb-16">Basic Information</h6>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Name <span class="text-danger">*</span></label>
									<input type="text" name="name" value="<?php echo e(old('name')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
									<?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Email</label>
									<input type="email" name="email" value="<?php echo e(old('email')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Phone</label>
									<input type="text" name="phone" value="<?php echo e(old('phone')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Customer</label>
									<select name="user_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
										<option value="">Select Customer</option>
										<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($user->id); ?>" <?php if(old('user_id') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-12">
									<hr class="my-4">
									<h6 class="fw-semibold mb-16">Lead Details</h6>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Lead Source</label>
									<select name="lead_source_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['lead_source_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
										<option value="">Select Source</option>
										<?php $__currentLoopData = $leadSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($source->id); ?>" <?php if(old('lead_source_id') == $source->id): echo 'selected'; endif; ?>><?php echo e($source->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<?php $__errorArgs = ['lead_source_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Lead Stage</label>
									<select name="lead_stage_id" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['lead_stage_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
										<option value="">Select Stage</option>
										<?php $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($stage->id); ?>" <?php if(old('lead_stage_id') == $stage->id): echo 'selected'; endif; ?>><?php echo e($stage->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<?php $__errorArgs = ['lead_stage_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Expected Value</label>
									<input type="number" name="expected_value" value="<?php echo e(old('expected_value')); ?>" step="0.01" min="0" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['expected_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
									<?php $__errorArgs = ['expected_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-lg-6">
									<label class="form-label text-secondary-light">Assign To Staff</label>
									<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
										<option value="">Unassigned</option>
										<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($user->id); ?>" <?php if(old('assigned_to') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
									<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
									<?php $__errorArgs = ['assigned_to'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

				<div class="col-12">
					<hr class="my-4">
					<h6 class="fw-semibold mb-16">Followup Planning</h6>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Next Followup Date</label>
					<input type="date" name="next_followup_date" value="<?php echo e(old('next_followup_date')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['next_followup_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
					<?php $__errorArgs = ['next_followup_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Next Followup Time</label>
					<input type="time" name="next_followup_time" value="<?php echo e(old('next_followup_time')); ?>" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['next_followup_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
					<?php $__errorArgs = ['next_followup_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

				<div class="col-lg-6">
					<label class="form-label text-secondary-light">Lead Score</label>
					<input type="number" name="lead_score" value="<?php echo e(old('lead_score', 0)); ?>" min="0" class="form-control bg-neutral-50 radius-12 h-56-px <?php $__errorArgs = ['lead_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
					<?php $__errorArgs = ['lead_score'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
				</div>

								<div class="col-12">
									<label class="form-label text-secondary-light">Notes</label>
									<textarea name="notes" rows="4" class="form-control bg-neutral-50 radius-12 <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('notes')); ?></textarea>
									<?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
								</div>

								<div class="col-12">
									<div class="d-flex gap-2">
										<button type="submit" class="btn btn-primary radius-12 h-56-px">Create Lead</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<!-- Manage Leads Tab -->
				<div class="tab-pane fade <?php echo e(request('tab') === 'manage' ? 'show active' : ''); ?>" id="lead-pane-manage" role="tabpanel" aria-labelledby="lead-tab-manage">
					<div class="card border-0 shadow-none bg-base mb-24">
						<div class="card-body p-24">
							<form method="GET" class="row g-3 align-items-end">
								<input type="hidden" name="tab" value="manage">
								<div class="col-lg-3">
									<label class="form-label text-secondary-light">Search</label>
									<div class="icon-field">
										<span class="icon top-50 translate-middle-y"><iconify-icon icon="mage:search"></iconify-icon></span>
										<input type="text" class="form-control h-56-px bg-neutral-50 radius-12" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, email, phone">
									</div>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Lead Source</label>
									<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_source_id">
										<option value="">All Sources</option>
										<?php $__currentLoopData = $leadSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($source->id); ?>" <?php if(request('lead_source_id') == $source->id): echo 'selected'; endif; ?>><?php echo e($source->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Lead Stage</label>
									<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_stage_id">
										<option value="">All Stages</option>
										<?php $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($stage->id); ?>" <?php if(request('lead_stage_id') == $stage->id): echo 'selected'; endif; ?>><?php echo e($stage->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Assigned To Staff</label>
									<select class="form-select h-56-px bg-neutral-50 radius-12" name="assigned_to">
										<option value="">All Staff</option>
										<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($user->id); ?>" <?php if(request('assigned_to') == $user->id): echo 'selected'; endif; ?>><?php echo e($user->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
								<div class="col-lg-3 d-flex gap-2">
									<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
									<a href="<?php echo e(route('admin.leads.index', ['tab' => 'manage'])); ?>" class="btn btn-outline-secondary h-56-px radius-12">
										<iconify-icon icon="mi:refresh"></iconify-icon>
									</a>
								</div>
							</form>
						</div>
					</div>

					<div class="card basic-data-table border-0">
						<div class="card-body p-0">
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Phone</th>
											<th>Source</th>
											<th>Stage</th>
											<th>Assigned To</th>
											<th>Expected Value</th>
											<th>Next Followup</th>
											<th>Score</th>
											<th>Created</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $__empty_1 = true; $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
											<tr>
												<td><?php echo e($lead->name); ?></td>
												<td><?php echo e($lead->email ?? '-'); ?></td>
												<td><?php echo e($lead->phone ?? '-'); ?></td>
												<td>
													<?php if($lead->source): ?>
														<span class="badge bg-info"><?php echo e($lead->source->name); ?></span>
													<?php else: ?>
														<span class="text-secondary-light">-</span>
													<?php endif; ?>
												</td>
												<td>
													<?php if($lead->stage): ?>
														<span class="badge bg-primary"><?php echo e($lead->stage->name); ?></span>
													<?php else: ?>
														<span class="text-secondary-light">-</span>
													<?php endif; ?>
												</td>
												<td>
													<?php if($lead->assignee): ?>
														<?php echo e($lead->assignee->name); ?>

													<?php else: ?>
														<span class="text-secondary-light">Unassigned</span>
													<?php endif; ?>
												</td>
												<td>
													<?php if($lead->expected_value): ?>
														<?php echo e(currency($lead->expected_value)); ?>

													<?php else: ?>
														<span class="text-secondary-light">-</span>
													<?php endif; ?>
												</td>
												<td>
													<?php if($lead->next_followup_date): ?>
														<span class="badge bg-secondary"><?php echo e($lead->next_followup_date?->format('d M Y')); ?> <?php echo e($lead->next_followup_time); ?></span>
													<?php else: ?>
														<span class="text-secondary-light">Not scheduled</span>
													<?php endif; ?>
												</td>
												<td>
													<span class="badge <?php echo e($lead->lead_score >= 70 ? 'bg-success' : ($lead->lead_score >= 40 ? 'bg-warning' : 'bg-danger')); ?>">
														<?php echo e($lead->lead_score ?? 0); ?>

													</span>
												</td>
												<td><?php echo e($lead->created_at?->format('d M Y H:i')); ?></td>
												<td>
													<div class="d-flex align-items-center gap-2">
														<button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#leadTools<?php echo e($lead->id); ?>" aria-expanded="false" aria-controls="leadTools<?php echo e($lead->id); ?>">
															Tools
														</button>
														<a href="<?php echo e(route('admin.leads.edit', $lead)); ?>?tab=manage" class="btn btn-sm btn-outline-primary" title="Edit">
															<iconify-icon icon="solar:pen-outline"></iconify-icon>
														</a>
														<form action="<?php echo e(route('admin.leads.destroy', $lead)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this lead?');">
															<?php echo csrf_field(); ?>
															<?php echo method_field('DELETE'); ?>
															<input type="hidden" name="tab" value="manage">
															<button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
																<iconify-icon icon="solar:trash-bin-outline"></iconify-icon>
															</button>
														</form>
													</div>
												</td>
											</tr>
											<tr class="collapse" id="leadTools<?php echo e($lead->id); ?>">
												<td colspan="11" class="bg-neutral-50">
													<div class="row g-4">
														<div class="col-lg-6">
															<h6 class="fw-semibold mb-12">Schedule Followup</h6>
															<?php echo $__env->make('admin.leads.partials.followup-form', ['lead' => $lead], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
														</div>
														<div class="col-lg-6">
															<h6 class="fw-semibold mb-12">Recent Activity</h6>
															<?php echo $__env->make('admin.leads.partials.activity-log', ['activities' => $lead->activities ?? collect()], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
											<tr>
												<td colspan="11" class="text-center py-4 text-secondary-light">No leads found.</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<?php if(isset($leads) && $leads->hasPages()): ?>
								<div class="p-3">
									<?php echo e($leads->appends(['tab' => 'manage'])->links()); ?>

								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<!-- Assign Leads Tab -->
				<div class="tab-pane fade <?php echo e(request('tab') === 'assign' ? 'show active' : ''); ?>" id="lead-pane-assign" role="tabpanel" aria-labelledby="lead-tab-assign">
					<div class="card border-0 shadow-none bg-base mb-24">
						<div class="card-body p-24">
							<form method="GET" class="row g-3 align-items-end">
								<input type="hidden" name="tab" value="assign">
								<div class="col-lg-3">
									<label class="form-label text-secondary-light">Lead Source</label>
									<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_source_id">
										<option value="">All Sources</option>
										<?php $__currentLoopData = $leadSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($source->id); ?>" <?php if(request('lead_source_id') == $source->id): echo 'selected'; endif; ?>><?php echo e($source->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
								<div class="col-lg-3">
									<label class="form-label text-secondary-light">Lead Stage</label>
									<select class="form-select h-56-px bg-neutral-50 radius-12" name="lead_stage_id">
										<option value="">All Stages</option>
										<?php $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<option value="<?php echo e($stage->id); ?>" <?php if(request('lead_stage_id') == $stage->id): echo 'selected'; endif; ?>><?php echo e($stage->name); ?></option>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</select>
								</div>
								<div class="col-lg-2">
									<label class="form-label text-secondary-light">Filter</label>
									<select class="form-select h-56-px bg-neutral-50 radius-12" name="filter">
										<option value="">All Leads</option>
										<option value="unassigned" <?php if(request('filter') === 'unassigned'): echo 'selected'; endif; ?>>Unassigned Only</option>
									</select>
								</div>
								<div class="col-lg-4 d-flex gap-2">
									<button class="btn btn-primary flex-grow-1 h-56-px radius-12">Filter</button>
									<a href="<?php echo e(route('admin.leads.index', ['tab' => 'assign'])); ?>" class="btn btn-outline-secondary h-56-px radius-12">
										<iconify-icon icon="mi:refresh"></iconify-icon>
									</a>
								</div>
							</form>
						</div>
					</div>

					<div class="card border-0">
						<div class="card-body p-24">
							<form method="POST" action="<?php echo e(route('admin.leads.assign.update')); ?>" id="assignForm">
								<?php echo csrf_field(); ?>
								<input type="hidden" name="tab" value="assign">
								<div class="row g-3 mb-4">
									<div class="col-lg-6">
										<label class="form-label text-secondary-light">Assign Selected Leads To Staff <span class="text-danger">*</span></label>
										<select name="assigned_to" class="form-select bg-neutral-50 radius-12 h-56-px" required>
											<option value="">Select Staff Member</option>
											<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										</select>
										<div class="form-text mt-1">Leads can only be assigned to staff members.</div>
									</div>
									<div class="col-lg-6 d-flex align-items-end">
										<button type="submit" class="btn btn-primary radius-12 h-56-px">Assign Selected Leads</button>
									</div>
								</div>

								<div class="table-responsive">
									<table class="table bordered-table mb-0 align-middle">
										<thead>
											<tr>
												<th width="50">
													<input type="checkbox" id="selectAll" class="form-check-input">
												</th>
												<th>Name</th>
												<th>Email</th>
												<th>Phone</th>
												<th>Source</th>
												<th>Stage</th>
												<th>Currently Assigned To</th>
												<th>Created</th>
											</tr>
										</thead>
										<tbody>
											<?php $__empty_1 = true; $__currentLoopData = $tab === 'assign' ? $assignLeads : $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
												<tr>
													<td>
														<input type="checkbox" name="lead_ids[]" value="<?php echo e($lead->id); ?>" class="form-check-input lead-checkbox">
													</td>
													<td><?php echo e($lead->name); ?></td>
													<td><?php echo e($lead->email ?? '-'); ?></td>
													<td><?php echo e($lead->phone ?? '-'); ?></td>
													<td>
														<?php if($lead->source): ?>
															<span class="badge bg-info"><?php echo e($lead->source->name); ?></span>
														<?php else: ?>
															<span class="text-secondary-light">-</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if($lead->stage): ?>
															<span class="badge bg-primary"><?php echo e($lead->stage->name); ?></span>
														<?php else: ?>
															<span class="text-secondary-light">-</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if($lead->assignee): ?>
															<?php echo e($lead->assignee->name); ?>

														<?php else: ?>
															<span class="text-secondary-light">Unassigned</span>
														<?php endif; ?>
													</td>
													<td><?php echo e($lead->created_at?->format('d M Y H:i')); ?></td>
												</tr>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
												<tr>
													<td colspan="8" class="text-center py-4 text-secondary-light">No leads found.</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</div>
								<?php if($tab === 'assign' && isset($assignLeads) && $assignLeads->hasPages()): ?>
									<div class="mt-3">
										<?php echo e($assignLeads->appends(['tab' => 'assign'])->links()); ?>

									</div>
								<?php elseif(isset($leads) && $leads->hasPages()): ?>
									<div class="mt-3">
										<?php echo e($leads->appends(['tab' => 'assign'])->links()); ?>

									</div>
								<?php endif; ?>
							</form>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(request('tab') === 'next-followups' ? 'show active' : ''); ?>" id="lead-pane-next" role="tabpanel" aria-labelledby="lead-tab-next">
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Upcoming Followups</h6>
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Lead</th>
											<th>Stage</th>
											<th>Assigned To</th>
											<th>Scheduled For</th>
											<th>Notes</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $__empty_1 = true; $__currentLoopData = $upcomingFollowups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $followup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
											<tr>
												<td>
													<div class="d-flex flex-column">
														<span class="fw-semibold"><?php echo e($followup->lead?->name); ?></span>
														<small class="text-secondary"><?php echo e($followup->lead?->email); ?></small>
													</div>
												</td>
												<td><span class="badge bg-primary"><?php echo e($followup->lead?->stage?->name ?? 'N/A'); ?></span></td>
												<td><?php echo e($followup->lead?->assignee?->name ?? 'Unassigned'); ?></td>
												<td>
													<strong><?php echo e($followup->followup_date?->format('d M Y')); ?></strong>
													<?php if($followup->followup_time): ?>
														<div class="text-secondary"><?php echo e($followup->followup_time); ?></div>
													<?php endif; ?>
												</td>
												<td><?php echo e(\Illuminate\Support\Str::limit($followup->notes, 60) ?: '-'); ?></td>
												<td class="text-end">
													<div class="d-flex justify-content-end gap-2">
														<form method="POST" action="<?php echo e(route('admin.leads.followups.complete', [$followup->lead_id, $followup->id])); ?>">
															<?php echo csrf_field(); ?>
															<button class="btn btn-sm btn-outline-success">Complete</button>
														</form>
														<form method="POST" action="<?php echo e(route('admin.leads.followups.cancel', [$followup->lead_id, $followup->id])); ?>">
															<?php echo csrf_field(); ?>
															<input type="hidden" name="reason" value="Cancelled from pipeline">
															<button class="btn btn-sm btn-outline-warning">Cancel</button>
														</form>
														<form method="POST" action="<?php echo e(route('admin.leads.followups.destroy', [$followup->lead_id, $followup->id])); ?>" onsubmit="return confirm('Remove this followup?');">
															<?php echo csrf_field(); ?>
															<?php echo method_field('DELETE'); ?>
															<button class="btn btn-sm btn-outline-danger">Delete</button>
														</form>
													</div>
												</td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
											<tr>
												<td colspan="6" class="text-center text-secondary-light py-4">No upcoming followups scheduled.</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<?php if($upcomingFollowups->hasPages()): ?>
								<div class="pt-3">
									<?php echo e($upcomingFollowups->appends(['tab' => 'next-followups'])->links()); ?>

								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(request('tab') === 'today-followups' ? 'show active' : ''); ?>" id="lead-pane-today" role="tabpanel" aria-labelledby="lead-tab-today">
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Today's Followups</h6>
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Lead</th>
											<th>Stage</th>
											<th>Assigned To</th>
											<th>Time</th>
											<th>Notes</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $__empty_1 = true; $__currentLoopData = $todayFollowups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $followup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
											<tr>
												<td><?php echo e($followup->lead?->name); ?></td>
												<td><span class="badge bg-info"><?php echo e($followup->lead?->stage?->name ?? 'N/A'); ?></span></td>
												<td><?php echo e($followup->lead?->assignee?->name ?? 'Unassigned'); ?></td>
												<td><?php echo e($followup->followup_time ?? 'Anytime'); ?></td>
												<td><?php echo e(\Illuminate\Support\Str::limit($followup->notes, 60) ?: '-'); ?></td>
												<td class="text-end">
													<form method="POST" action="<?php echo e(route('admin.leads.followups.complete', [$followup->lead_id, $followup->id])); ?>" class="d-inline">
														<?php echo csrf_field(); ?>
														<button class="btn btn-sm btn-outline-success">Mark Done</button>
													</form>
												</td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
											<tr>
												<td colspan="6" class="text-center text-secondary-light py-4">No followups scheduled for today.</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<?php if($todayFollowups->hasPages()): ?>
								<div class="pt-3">
									<?php echo e($todayFollowups->appends(['tab' => 'today-followups'])->links()); ?>

								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(request('tab') === 'analytics' ? 'show active' : ''); ?>" id="lead-pane-analytics" role="tabpanel" aria-labelledby="lead-tab-analytics">
					<div class="row g-3 mb-24">
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Total Leads</p>
									<h4 class="fw-semibold mb-0"><?php echo e($analytics['total_leads'] ?? 0); ?></h4>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Won Leads</p>
									<h4 class="fw-semibold mb-0 text-success"><?php echo e($analytics['won_leads'] ?? 0); ?></h4>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Lost Leads</p>
									<h4 class="fw-semibold mb-0 text-danger"><?php echo e($analytics['lost_leads'] ?? 0); ?></h4>
								</div>
							</div>
						</div>
						<div class="col-md-3">
							<div class="card border-0 shadow-sm h-100">
								<div class="card-body">
									<p class="text-secondary-light mb-1">Conversion Rate</p>
									<h4 class="fw-semibold mb-0"><?php echo e($analytics['conversion_rate'] ?? 0); ?>%</h4>
								</div>
							</div>
						</div>
					</div>
					<div class="card border-0 mb-24">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-3">Leads by Stage</h6>
							<div class="table-responsive">
								<table class="table mb-0">
									<thead>
										<tr>
											<th>Stage</th>
											<th>Status</th>
											<th class="text-end">Count</th>
										</tr>
									</thead>
									<tbody>
										<?php $__currentLoopData = $analytics['stage_stats'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
											<tr>
												<td><?php echo e($stage->name); ?></td>
												<td>
													<?php if($stage->is_won): ?>
														<span class="badge bg-success">Won</span>
													<?php elseif($stage->is_lost): ?>
														<span class="badge bg-danger">Lost</span>
													<?php else: ?>
														<span class="badge bg-secondary">Open</span>
													<?php endif; ?>
												</td>
												<td class="text-end"><?php echo e($stage->leads_count ?? 0); ?></td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-3">Leads by Source</h6>
							<div class="row g-3">
								<?php $__currentLoopData = $analytics['source_stats'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
									<div class="col-md-3">
										<div class="border rounded-3 p-3 h-100">
											<p class="text-secondary-light mb-1"><?php echo e($source->name); ?></p>
											<h5 class="fw-semibold mb-0"><?php echo e($source->leads_count ?? 0); ?></h5>
										</div>
									</div>
								<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
							</div>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(request('tab') === 'trash' ? 'show active' : ''); ?>" id="lead-pane-trash" role="tabpanel" aria-labelledby="lead-tab-trash">
					<div class="card border-0">
						<div class="card-body p-24">
							<h6 class="fw-semibold mb-16">Trashed Leads</h6>
							<div class="table-responsive">
								<table class="table bordered-table mb-0 align-middle">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Stage</th>
											<th>Deleted At</th>
											<th class="text-end">Actions</th>
										</tr>
									</thead>
									<tbody>
										<?php $__empty_1 = true; $__currentLoopData = $trashedLeads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
											<tr>
												<td><?php echo e($lead->name); ?></td>
												<td><?php echo e($lead->email ?? '-'); ?></td>
												<td><?php echo e($lead->stage?->name ?? '-'); ?></td>
												<td><?php echo e($lead->deleted_at?->format('d M Y H:i')); ?></td>
												<td class="text-end">
													<form method="POST" action="<?php echo e(route('admin.leads.restore', $lead->id)); ?>" class="d-inline">
														<?php echo csrf_field(); ?>
														<button class="btn btn-sm btn-outline-success">Restore</button>
													</form>
													<form method="POST" action="<?php echo e(route('admin.leads.force-delete', $lead->id)); ?>" class="d-inline" onsubmit="return confirm('Permanently delete this lead?');">
														<?php echo csrf_field(); ?>
														<?php echo method_field('DELETE'); ?>
														<button class="btn btn-sm btn-outline-danger">Delete</button>
													</form>
												</td>
											</tr>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
											<tr>
												<td colspan="5" class="text-center text-secondary-light py-4">Trash is empty.</td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</div>
							<?php if($trashedLeads->hasPages()): ?>
								<div class="pt-3">
									<?php echo e($trashedLeads->appends(['tab' => 'trash'])->links()); ?>

								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="tab-pane fade <?php echo e(request('tab') === 'import-export' ? 'show active' : ''); ?>" id="lead-pane-import" role="tabpanel" aria-labelledby="lead-tab-import">
					<div class="row g-3">
						<div class="col-lg-6">
							<div class="card border-0 h-100">
								<div class="card-body p-24">
									<h6 class="fw-semibold mb-3">Import Leads</h6>
									<form method="POST" action="<?php echo e(route('admin.leads.import')); ?>" enctype="multipart/form-data" class="row g-3">
										<?php echo csrf_field(); ?>
										<div class="col-12">
											<label class="form-label text-secondary-light">CSV File</label>
											<input type="file" name="file" accept=".csv" class="form-control" required>
											<small class="text-secondary-light d-block mt-1">Download the exported template, update rows, then upload to import.</small>
										</div>
										<div class="col-12">
											<label class="form-label text-secondary-light">Mode</label>
											<select name="mode" class="form-select">
												<option value="create">Create only (skip existing emails)</option>
												<option value="update">Update existing leads by email</option>
												<option value="replace">Replace (soft delete all leads before importing)</option>
											</select>
										</div>
										<div class="col-12">
											<button class="btn btn-primary w-100">Upload &amp; Process</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="card border-0 h-100">
								<div class="card-body p-24">
									<h6 class="fw-semibold mb-3">Export Leads</h6>
									<form method="GET" action="<?php echo e(route('admin.leads.export')); ?>" class="row g-3">
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Lead Stage</label>
											<select name="lead_stage_id" class="form-select">
												<option value="">All</option>
												<?php $__currentLoopData = $leadStages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option value="<?php echo e($stage->id); ?>"><?php echo e($stage->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Lead Source</label>
											<select name="lead_source_id" class="form-select">
												<option value="">All</option>
												<?php $__currentLoopData = $leadSources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $source): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option value="<?php echo e($source->id); ?>"><?php echo e($source->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Assigned Staff</label>
											<select name="assigned_to" class="form-select">
												<option value="">All</option>
												<?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label text-secondary-light">Format</label>
											<select name="format" class="form-select">
												<option value="csv">CSV</option>
											</select>
										</div>
										<div class="col-12">
											<button class="btn btn-outline-primary w-100">Download Export</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
	// Handle select all checkbox for assign tab
	const selectAllCheckbox = document.getElementById('selectAll');
	if (selectAllCheckbox) {
		selectAllCheckbox.addEventListener('change', function() {
			const checkboxes = document.querySelectorAll('.lead-checkbox');
			checkboxes.forEach(checkbox => {
				checkbox.checked = this.checked;
			});
		});
	}

	// Handle assign form submission
	const assignForm = document.getElementById('assignForm');
	if (assignForm) {
		assignForm.addEventListener('submit', function(e) {
			const checked = document.querySelectorAll('.lead-checkbox:checked');
			if (checked.length === 0) {
				e.preventDefault();
				alert('Please select at least one lead to assign.');
				return false;
			}
		});
	}

	// Preserve tab state on form submissions
	document.querySelectorAll('form').forEach(form => {
		form.addEventListener('submit', function() {
			const activeTab = document.querySelector('.nav-pills .active');
			if (activeTab) {
				const tabId = activeTab.id.replace('lead-tab-', '');
				const hiddenInput = document.createElement('input');
				hiddenInput.type = 'hidden';
				hiddenInput.name = 'tab';
				hiddenInput.value = tabId;
				form.appendChild(hiddenInput);
			}
		});
	});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/admin/leads/index-tabbed.blade.php ENDPATH**/ ?>