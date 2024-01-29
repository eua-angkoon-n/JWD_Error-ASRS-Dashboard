<style type="text/css">
  @import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@400&display=swap');
  
  body{
      font-size:0.85rem;
      /*font-family: "Noto Sans Thai",sans-serif;*/
      font-family: 'Sarabun', sans-serif;
      font-style: normal;
      font-weight:500;
  }

  .dataTables_length,
  .form-control-sm {
    font-size: 0.85rem;
    /* 40px/16=2.5em */
  }
  
  .table,
  .dataTable tr td {
    padding: 0.35rem 0.50rem;
    margin: 0;
  }
  
  .btn-sm {
    padding: 0.10rem 0.40rem 0.20rem 0.40rem;
    margin: 0.0rem 0.0rem;
  }
  
  .dt-buttons button {
    font-size: 0.85rem;
    /* 40px/16=2.5em */
  }

  .side-gradient {
  background: linear-gradient(to bottom, #000043 80%, #0054FF, #6d7fae);
  }

  .content-gradient {
  background: linear-gradient(to top, #FFFFFF, #E6E7E8);
}

.lds-ring {
  display: inline-block;
  position: relative;
  width: 80px;
  height: 80px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 64px;
  height: 64px;
  margin: 8px;
  border: 8px solid #E6E7E8;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #E6E7E8 transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%; /* Adjust the height as needed */
}



</style>