					<nav class="main-menu-container nav nav-pills flex-column sub-open">
						<div class="slide-left" id="slide-left">
							<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewbox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
						</div>
						<ul class="main-menu">
							<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name">اصلی</span></li>
							<!-- End::slide__category -->
					
						 @if(Auth::check() && Auth::user()->section === 'Factory')
							<!-- Start::slide -->
							<li class="slide">
								<a href="{{ route('fa_dashboard.index')}}" class="side-menu__item">
									<i class="side-menu__icon bx bx-home-alt"></i>
								<span class="side-menu__label">  داشبورد کارخانه</span>
								</a>
							</li>
						@else
							<!-- Start::slide -->
							<li class="slide">
								<a href="{{ route('al_dashboard.index')}}" class="side-menu__item">
									<i class="side-menu__icon bx bx-home-alt"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label">  داشبورد تامین المونیم</span>
								</a>
							</li>
						@endif



						@if(Auth::check() && Auth::user()->section === 'Trade_al')
							<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name">ثبت خرید و فروش المونیم</span></li>
							<!-- End::slide__category -->
							<li class="slide has-sub">
								<a href="javascript:void(0);" class="side-menu__item">
									<i class="side-menu__icon ri-shopping-bag-line"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="m8.643 3.146l-1.705.788C4.313 5.147 3 5.754 3 6.75s1.313 1.603 3.938 2.816l1.705.788c1.652.764 2.478 1.146 3.357 1.146s1.705-.382 3.357-1.146l1.705-.788C19.687 8.353 21 7.746 21 6.75s-1.313-1.603-3.938-2.816l-1.705-.788C13.705 2.382 12.879 2 12 2s-1.705.382-3.357 1.146"></path><path d="M20.788 11.097c.141.199.212.406.212.634c0 .982-1.313 1.58-3.938 2.776l-1.705.777c-1.652.753-2.478 1.13-3.357 1.13s-1.705-.377-3.357-1.13l-1.705-.777C4.313 13.311 3 12.713 3 11.731c0-.228.07-.435.212-.634"></path><path d="M20.377 16.266c.415.331.623.661.623 1.052c0 .981-1.313 1.58-3.938 2.776l-1.705.777C13.705 21.624 12.879 22 12 22s-1.705-.376-3.357-1.13l-1.705-.776C4.313 18.898 3 18.299 3 17.318c0-.391.208-.72.623-1.052"></path></g></svg> -->
									<span class="side-menu__label">  خرید المونیم</span>
									<i class="ri-arrow-down-s-line side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0)">تامین المونیم</a>
									</li>
									<li class="slide">
										<a href="{{route('supplier.index')}}" class="side-menu__item">فروشنده گان</a>
									</li>
									<li class="slide2">
										<a href="{{ route('transaction.index', ['type' => 'purchase']) }}" class="side-menu__item"> معاملات فروشنده</a>
									</li>
									<li class="slide">
										<a href="{{route('payment.index', ['type' => 'purchase'])}}" class="side-menu__item">پرداختی ها</a>
									</li>
									<li class="slide">
										<a href="{{route('aluminum_expenses.index', ['type' => 'purchase_expense'])}}" class="side-menu__item"> مصارف خرید</a>
									</li>
								</ul>
							</li>
			
							<li class="slide has-sub">
								<a href="javascript:void(0);" class="side-menu__item">
									<i class="side-menu__icon ri-price-tag-line"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="m8.643 3.146l-1.705.788C4.313 5.147 3 5.754 3 6.75s1.313 1.603 3.938 2.816l1.705.788c1.652.764 2.478 1.146 3.357 1.146s1.705-.382 3.357-1.146l1.705-.788C19.687 8.353 21 7.746 21 6.75s-1.313-1.603-3.938-2.816l-1.705-.788C13.705 2.382 12.879 2 12 2s-1.705.382-3.357 1.146"></path><path d="M20.788 11.097c.141.199.212.406.212.634c0 .982-1.313 1.58-3.938 2.776l-1.705.777c-1.652.753-2.478 1.13-3.357 1.13s-1.705-.377-3.357-1.13l-1.705-.777C4.313 13.311 3 12.713 3 11.731c0-.228.07-.435.212-.634"></path><path d="M20.377 16.266c.415.331.623.661.623 1.052c0 .981-1.313 1.58-3.938 2.776l-1.705.777C13.705 21.624 12.879 22 12 22s-1.705-.376-3.357-1.13l-1.705-.776C4.313 18.898 3 18.299 3 17.318c0-.391.208-.72.623-1.052"></path></g></svg> -->
									<span class="side-menu__label">فروش المونیم</span>
									<i class="ri-arrow-down-s-line side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0)">تامین المونیم</a>
									</li>

									<li class="slide2">
										<a href="{{route('client.index')}}" class="side-menu__item">مشتری ها</a>
									</li>
									<li class="slide">
										<a href="{{ route('transaction.index', ['type' => 'sale']) }}" class="side-menu__item"> معاملات مشتری</a>
									</li>

									<li class="slide">
										<a href="{{route('payment.index', ['type' => 'sale'])}}" class="side-menu__item">پرداختی ها</a>
									</li>

									<li class="slide">
										<a href="{{route('aluminum_expenses.index', ['type' => 'sell_expense'])}}" class="side-menu__item"> مصارف فروش</a>
									</li>
								</ul>
							</li>

							<!-- Start::slide -->

						@else
							<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name"> مدیریت کارخانه</span></li>
							<!-- End::slide__category -->

							<!-- Start::slide -->
							<li class="slide">
								<a href="{{route('factory-purchases.index')}}" class="side-menu__item">
									<i class="side-menu__icon bi bi-bag"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label"> خرید المونیم</span>
								</a>
							</li>

							<li class="slide has-sub">
								<a href="javascript:void(0);" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="m8.643 3.146l-1.705.788C4.313 5.147 3 5.754 3 6.75s1.313 1.603 3.938 2.816l1.705.788c1.652.764 2.478 1.146 3.357 1.146s1.705-.382 3.357-1.146l1.705-.788C19.687 8.353 21 7.746 21 6.75s-1.313-1.603-3.938-2.816l-1.705-.788C13.705 2.382 12.879 2 12 2s-1.705.382-3.357 1.146"></path><path d="M20.788 11.097c.141.199.212.406.212.634c0 .982-1.313 1.58-3.938 2.776l-1.705.777c-1.652.753-2.478 1.13-3.357 1.13s-1.705-.377-3.357-1.13l-1.705-.777C4.313 13.311 3 12.713 3 11.731c0-.228.07-.435.212-.634"></path><path d="M20.377 16.266c.415.331.623.661.623 1.052c0 .981-1.313 1.58-3.938 2.776l-1.705.777C13.705 21.624 12.879 22 12 22s-1.705-.376-3.357-1.13l-1.705-.776C4.313 18.898 3 18.299 3 17.318c0-.391.208-.72.623-1.052"></path></g></svg>
									<span class="side-menu__label">مدیریت کارمندان</span>
									<i class="ri-arrow-down-s-line side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0)">مدیریت کارمندان</a>
									</li>

									<li class="slide has-sub">
										<a href="javascript:void(0);" class="side-menu__item">کارمندان											<i class="ri-arrow-down-s-line side-menu__angle"></i></a>
										<ul class="slide-menu child2">
											<li class="slide">
												<a href="{{ route('employee.index', ['contract_type' => 'ejaraei']) }}" class="side-menu__item">کارمندان اجاره ایی </a>
											</li>
											<li class="slide2">
												<a href="{{ route('employee.index', ['contract_type' => 'rozmozd']) }}" class="side-menu__item"> کارمندان روزمزد</a>
											</li>

										</ul>
									</li>

									<li class="slide">
										<a href="{{route('attendance.index')}}" class="side-menu__item"> حاضری</a>
									</li>
								</ul>
							</li>
	
							<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name"> تولیدات</span></li>
							<!-- End::slide__category -->
							
							<!-- Start::slide -->
							<li class="slide">
								<a href="{{ route('pouring_pot.index') }}" class="side-menu__item">
									<i class="side-menu__icon bx bx-error-circle"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label">ریخت</span>
								</a>
							</li>

							<!-- Start::slide -->
							<li class="slide">
								<a href="{{ route('design_pot.index') }}" class="side-menu__item">
									<i class="side-menu__icon fe fe-alert-triangle"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label">دیزاین</span>
								</a>
							</li>
							
							<li class="slide has-sub">
								<a href="javascript:void(0);" class="side-menu__item">
									<i class="side-menu__icon bx bx-table"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="m8.643 3.146l-1.705.788C4.313 5.147 3 5.754 3 6.75s1.313 1.603 3.938 2.816l1.705.788c1.652.764 2.478 1.146 3.357 1.146s1.705-.382 3.357-1.146l1.705-.788C19.687 8.353 21 7.746 21 6.75s-1.313-1.603-3.938-2.816l-1.705-.788C13.705 2.382 12.879 2 12 2s-1.705.382-3.357 1.146"></path><path d="M20.788 11.097c.141.199.212.406.212.634c0 .982-1.313 1.58-3.938 2.776l-1.705.777c-1.652.753-2.478 1.13-3.357 1.13s-1.705-.377-3.357-1.13l-1.705-.777C4.313 13.311 3 12.713 3 11.731c0-.228.07-.435.212-.634"></path><path d="M20.377 16.266c.415.331.623.661.623 1.052c0 .981-1.313 1.58-3.938 2.776l-1.705.777C13.705 21.624 12.879 22 12 22s-1.705-.376-3.357-1.13l-1.705-.776C4.313 18.898 3 18.299 3 17.318c0-.391.208-.72.623-1.052"></path></g></svg> -->
									<span class="side-menu__label"> گزارشات</span>
									<i class="ri-arrow-down-s-line side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0)">  گزارشات</a>
									</li>

									<li class="slide">
										<a href="{{route('al.report')}}" class="side-menu__item">   گزارش خرید المونیم</a>
									</li>

									<li class="slide">
										<a href="{{route('pouring.report')}}" class="side-menu__item">  گزارش ریخت</a>
									</li>

									<li class="slide">
										<a href="{{route('design.report')}}" class="side-menu__item">  گزارش دیزاین</a>
									</li>

									<li class="slide">
										<a href="{{route('sale.report')}}" class="side-menu__item">  گزارش فروشات</a>
									</li>
								</ul>
							</li>
							<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name"> فروشات</span></li>
							<!-- End::slide__category -->
							
							<!-- Start::slide -->
							<li class="slide">
								<a href="{{ route('customer.index') }}" class="side-menu__item">
									<i class="side-menu__icon ri-group-fill"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label">مشتری</span>
								</a>
							</li>

							<!-- Start::slide -->
							<li class="slide">
								<a href="{{ route('sales.index') }}" class="side-menu__item">
									<i class="side-menu__icon ri-shopping-bag-4-line"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label">فروشات</span>
								</a>
							</li>

							<!-- Start::slide -->
							<li class="slide">
								<a href="{{route('factory_expenses.index')}}" class="side-menu__item">
									<i class="side-menu__icon ri-wallet-line"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label">مصارف کارخانه</span>
								</a>
							</li>

							@if(Auth::check() && Auth::user()->is_admin === 1)
							<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name"> مدیریت کاربران</span></li>
							<!-- End::slide__category -->
							
							<!-- Start::slide -->
							<li class="slide">
								<a href="{{route('user.index')}}" class="side-menu__item">
									<i class="side-menu__icon ri-pass-valid-line"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg> -->
									<span class="side-menu__label"> کاربران</span>
								</a>
							</li>
							@endif
														<!-- Start::slide__category -->
							<li class="slide__category"><span class="category-name">  ابزار های مدیریتی</span></li>
							<!-- End::slide__category -->

							<li class="slide has-sub">
								<a href="javascript:void(0);" class="side-menu__item">
									<i class="side-menu__icon ri-edit-line"></i>
									<!-- <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" color="currentColor"><path d="m8.643 3.146l-1.705.788C4.313 5.147 3 5.754 3 6.75s1.313 1.603 3.938 2.816l1.705.788c1.652.764 2.478 1.146 3.357 1.146s1.705-.382 3.357-1.146l1.705-.788C19.687 8.353 21 7.746 21 6.75s-1.313-1.603-3.938-2.816l-1.705-.788C13.705 2.382 12.879 2 12 2s-1.705.382-3.357 1.146"></path><path d="M20.788 11.097c.141.199.212.406.212.634c0 .982-1.313 1.58-3.938 2.776l-1.705.777c-1.652.753-2.478 1.13-3.357 1.13s-1.705-.377-3.357-1.13l-1.705-.777C4.313 13.311 3 12.713 3 11.731c0-.228.07-.435.212-.634"></path><path d="M20.377 16.266c.415.331.623.661.623 1.052c0 .981-1.313 1.58-3.938 2.776l-1.705.777C13.705 21.624 12.879 22 12 22s-1.705-.376-3.357-1.13l-1.705-.776C4.313 18.898 3 18.299 3 17.318c0-.391.208-.72.623-1.052"></path></g></svg> -->
									<span class="side-menu__label"> متفرقه</span>
									<i class="ri-arrow-down-s-line side-menu__angle"></i>
								</a>
								<ul class="slide-menu child1">
									<li class="slide side-menu__label1">
										<a href="javascript:void(0)">  متفرقه</a>
									</li>

									<li class="slide">
										<a href="{{route('pot_types.index')}}" class="side-menu__item"> ثبت انواع جنس</a>
									</li>

									<li class="slide">
										<a href="{{route('designs.index')}}" class="side-menu__item"> ثبت دیزاین</a>
									</li>
								</ul>
							</li>
						@endif
							<!-- Start::slide -->
							<!-- <li class="slide">
								<a href="{{route('employee.index')}}" class="side-menu__item">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" width="1em" height="1em" viewbox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 11v4c0 3.3 0 4.95 1.025 5.975S7.7 22 11 22h2c3.3 0 4.95 0 5.975-1.025S20 18.3 20 15v-4M3 9c0-.748 0-1.122.201-1.4a1.4 1.4 0 0 1 .549-.44C4.098 7 4.565 7 5.5 7h13c.935 0 1.402 0 1.75.16c.228.106.417.258.549.44C21 7.878 21 8.252 21 9s0 1.121-.201 1.4a1.4 1.4 0 0 1-.549.44c-.348.16-.815.16-1.75.16h-13c-.935 0-1.402 0-1.75-.16a1.4 1.4 0 0 1-.549-.44C3 10.121 3 9.748 3 9m3-5.214C6 2.799 6.8 2 7.786 2h.357A3.857 3.857 0 0 1 12 5.857V7H9.214A3.214 3.214 0 0 1 6 3.786m12 0C18 2.799 17.2 2 16.214 2h-.357A3.857 3.857 0 0 0 12 5.857V7h2.786A3.214 3.214 0 0 0 18 3.786M12 11v11" color="currentColor"></path></svg>
									<span class="side-menu__label">کارمندان</span>
								</a>
							</li> -->
							<!-- End::slide -->
				

	
						</ul>
						<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewbox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
					</nav>