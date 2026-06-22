<footer class="mt-20 border-t border-gray-100 bg-[#F9F9F9]">
    <div class="container mx-auto px-6 py-12">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            
            <div class="flex flex-col items-center md:items-start">
                <p class="text-[10px] font-black text-gray-400 tracking-[0.3em] uppercase">
                    CV. SINAR TAXI • 
                    <?php 
                        $currentPage = basename($_SERVER['PHP_SELF']);
                        if ($currentPage == 'checkout.php') {
                            echo '<span class="text-gray-300">SECURE CHECKOUT</span>';
                        } elseif ($currentPage == 'order.php' || $currentPage == 'perjalanan.php') {
                            echo '<span class="text-gray-300">MANAGE YOUR TRIP</span>';
                        } else {
                            echo '<span class="text-gray-300">OFFICIAL BOOKING</span>';
                        }
                    ?>
                </p>
                <p class="text-[8px] font-bold text-gray-300 uppercase mt-1 tracking-widest">© 2026 All Rights Reserved</p>
            </div>

            <div class="flex space-x-8">
                <a href="bantuan.php" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-yellow-600 transition-colors">Bantuan</a>
                <a href="snk.php" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-yellow-600 transition-colors">S & K</a>
            </div>

        </div>
    </div>
</footer>