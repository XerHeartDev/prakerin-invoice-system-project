function currencyUnformat(currency) {
  if (!currency) return 0;
  return (
    parseFloat(
      currency
        .replace(/\./g, "") // hapus semua titik ribuan
        .replace(",", ".") // ubah koma ke titik
        .replace(/[^0-9.]/g, "") // jaga-jaga hapus sisa simbol selain angka, titik, dan minus
    ) || 0
  );
}

function currencyFormat(number) {
  if (!number) return "0,00";
  return new Intl.NumberFormat("id-ID", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  }).format(number);
}

function currencyRupiah(currency) {
  return new Intl.NumberFormat("id-ID", {
    style: "currency",
    currency: "IDR",
    minimumFractionDigits: 0,
  }).format(currency);
}
