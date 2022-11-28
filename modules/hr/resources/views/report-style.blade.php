<style>
   @import url('https://fonts.maateen.me/solaiman-lipi/font.css');

   * {
      font-family: 'SolaimanLipi', sans-serif;
   }

   .reportTable {
      margin-bottom: 1rem;
      width: 100%;
      max-width: 100%;
      font-size: 12px;
      border-collapse: collapse;
   }

   .reportTable thead,
   .reportTable tbody,
   .reportTable th {
      padding: 3px;
      font-size: 12px;
      text-align: center;
   }

   .reportTable th,
   .reportTable td {
      border: 1px solid #000;
   }

   .table td, .table th {
      padding: 0.1rem;
      vertical-align: middle;
   }

   .spacer {
      height: .5rem;
   }

   @page {
      margin: 100px 35px 35px 35px;!important;
   }

   header {
      position: fixed;
      top: -100px;
      left: 0;
      right: 0;
      text-align: center;
      height: 50px;
   }

   footer {
      position: fixed;
      bottom: -50px;
      font-size: 12px;
      left: 0;
      right: 0;
      text-align: center;
      height: 50px;
   }

   header h4 {
      margin: 2px 0 2px 0;
   }

   header h2 {
      margin-bottom: 2px;
   }

   .spacer {
      height: .5rem;
   }

   .text-center {
      text-align: center;
   }

   .list-style-none {
      list-style: none;
   }

   .salary-head {
      min-width: 400px;
      display: inline-block;
   }

   th, td, p, li {
      font-size: 12px !important;
   }

   .clearfix {
      overflow: auto;
   }

   .clearfix::after {
      content: "";
      clear: both;
      display: table;
   }

   @media print {
      @page {
         size: landscape;
         -webkit-transform: rotate(-90deg);
         -moz-transform: rotate(-90deg);
         filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
         -webkit-font-smoothing: antialiased;
         page-break-after: always;
      }

      .main {
         margin: 10px;
      }

      tr {
         page-break-inside: avoid;
         page-break-after: auto
      }

      table {
         page-break-inside: auto
      }

   }
</style>
