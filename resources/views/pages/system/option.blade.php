@extends("layouts.main")

@section('title', 'Setup Options')

@section('breadcump')
<div class="col-sm-6">
    <h1 class="m-0">{{ __('Setup Options') }}</h1>
</div>
<div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('backend.dashboard.index') }}">{{ __('Home') }}</a></li>
        <li class="breadcrumb-item active">{{ __('Setup Options') }}</li>
    </ol>
</div>
@endsection

@section('main')
<div class="row justify-content-center">
    <div class="col-7">
        <!-- small box -->
        <!-- <div class="small-box p-4 list-container">
            <div class="custom-control custom-checkbox">
                <input id="modifySalespersonInfo" type="checkbox" class="custom-control-input">
                <label for="modifySalespersonInfo" class="custom-control-label">Add or Change Salesperson Info</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="modifyByPassItem" type="checkbox" class="custom-control-input">
                <label for="modifyByPassItem" class="custom-control-label">Add / Change ByPass Items</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="modifySalesGroup" type="checkbox" class="custom-control-input">
                <label for="modifySalesGroup" class="custom-control-label">Add or Edit Sales Groups</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="loadAllDataFromQB" type="checkbox" class="custom-control-input">
                <label for="loadAllDataFromQB" class="custom-control-label">Load All Necessary data from QuickBooks</label>
            </div>
            <div class="custom-control custom-checkbox">
                <input id="commOptions" type="checkbox" class="custom-control-input">
                <label for="commOptions" class="custom-control-label">Commission Options</label>
            </div>
        </div> -->
    </div>
<style>
    span {
    margin-left: 21px;
    font-size: 17px;
    font-weight: 700;
}
.zip-code {
    position: absolute;
    bottom: 94%;
    left: 16px;
    font-weight: 600;
}
.bypass-here.card {
    padding: 20px;
}
h3.text-center {
    font-size: 35px;
    text-transform: uppercase;
    font-weight: 600;
    background: #117a8b;
    color: white;
}
.col-md-6.text-center.iteam {
    font-size: 22px;
    text-transform: capitalize;
    font-weight: 500;
    color: #4b4a4a;
}

h2.by-here {
    font-size: 16px;
    margin-top: 20px;
    font-weight: 600;
    color: #212529cc;
}
.group-sale.card {
    padding: 23px;
}
.group-sale.card p {
    margin-top: 0;
    margin-bottom: 1rem;
    font-size: 21px;
    font-weight: 600;
    color: #5c5b5b;
}
.date-enter.card {
    padding: 21px;
}
.date-enter.card p {
    margin-top: 0;
    margin-bottom: 1rem;
    background: #e2e217;
    color: white;
    font-size: 15px;
    text-transform: capitalize;
    text-align: center;
    padding: 9px;
}
input#birthday {
    margin-left: 36px;
}
input[type="submit"] {
    background: #117a8b;
    color: white;
    border: none;
    padding: 4px 16px;
    /* border: coral; */
    border-radius: 5px;
}
.btn-primary {
    color: #fff!important;
    background-color: #117a8b !important;
    border-color: #17a2b8 !important;
    box-shadow: none !important;
}
@media screen and (max-width: 767px) {
    .col-md-6.text-right.all-button {
    display: flex !important;

}
button.btn.btn-primary {
    font-size: 12px;
}
button.btn.btn-danger{
    font-size: 12px;
    margin-left: 2px
}
button.btn.btn-success{
    font-size: 12px;
    margin-left: 2px
}
.col-md-12.text-right.mt-4.pb-3.all-button {
    display:flex !important;
}
button.btn.btn-danger.ml-2.fillter {
    margin-top: 14px;
    font-size: 17px;
}
input#birthday {
    margin-left: 0!important;
}
input.form-check-input {
    margin-left: 0px!important;
}
.value-here input.form-check-input {
    margin-left: 0px!important;


}

}
</style>

    <!-----------

    Start tab work here.....

    ------------->

    <!----------------------->

<div class="tabs2"style="width:100%";>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            <div class="btn-group  mb-2" style="background: #17a2b8;border-radius:5px;">
        <ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home"class="btn btn-info p-2 active">Add or Change Salesperson Info</a></li>
  <li><a data-toggle="tab" href="#menu1"class="btn btn-info p-2">Add / Change ByPass Items</a></li>
  <li><a data-toggle="tab" href="#menu2"class="btn btn-info p-2">Add or Edit Sales Groups</a></li>
  <li><a data-toggle="tab" href="#menu3"class="btn btn-info p-2">Load All Necessary data from QuickBooks</a></li>
  <li><a data-toggle="tab" href="#menu4"class="btn btn-info p-2">Commission Options</a></li>
</ul>
            </div>

<div class="tab-content"style="width:98%";>
  <div id="home" class="tab-pane fade in active show card p-5">
  <h3 class="text-center">Enter & Edit</h3>
  <form class="mb-4">
  <select name="cars" class="custom-select">
    <option selected>Find Sales Rep Id</option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
</form>
<div class="row">
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Name</label>
          </div>
          <div class="col-md-8">
    <input type="input" class="form-control" placeholder="Name here" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
  <div class="row">
          <div class="col-md-4">
    <label for="email">commission Rate</label>
          </div>
          <div class="col-md-8">
    <input type="numder" class="form-control" placeholder="35" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Adress Line 1</label>
          </div>
          <div class="col-md-8">
    <input type="input" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Rep Manager</label>
          </div>
          <div class="col-md-8">
          <form>
  <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
</form>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Adress Line 2</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Rep Manager rate</label>
          </div>
          <div class="col-md-8">
    <input type="number" class="form-control" placeholder="3" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">City</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="email">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-9">
          <input class="form-check-input ml-0" type="checkbox">
         <span> Automatic Rep splits</span>
          </div>
          <div class="col-md-3 text-left">

          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">State</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Zip Code</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Telephone No</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Extension</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Adress line 3</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Countery code</label>
          </div>
          <div class="col-md-8">
    <input type="Number" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Email address:</label>
          </div>
          <div class="col-md-8">
    <input type="email" class="form-control" placeholder="Enter email" id="email">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Sales Group</label>
          </div>
          <div class="col-md-8">
          <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
           <div class="col-md-9">
          <input class="form-check-input ml-0" type="checkbox">
         <span>Export Data</span>
          </div>
          <div class="col-md-3">

          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">

    </div>
    <div class="col-md-6 text-right all-button">
    <button type="button" class="btn btn-primary">Save Record</button>
<button type="button" class="btn btn-danger">Delete Record</button>
<button type="button" class="btn btn-success">Close Form</button>
    </div>
</div>
  </div>

  <div id="menu1" class="tab-pane fade card p-5">
    <div class="add-bypass ">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="bypass-here card">
              <h3 class="text-center">By Pass</h3>
              <div class="row">
                  <div class="col-md-6 text-center iteam">
                      Iteam referrence to bypass
                  </div>
                  <div class="col-md-6 text-center">
                  <div class="form-check">
  <label class="form-check-label mt-2">
    <input type="checkbox" class="form-check-input" value="">By pass description
  </label>
                  </div>

              </div>
<div class="col-md-12 text-center">
    <h2 class="by-here">ByPass Iteams</h2>
</div>
              <div class="col-md-4 text-center">
                  Iteam Refname
              </div>
              <div class="col-md-8 text-center">
              <input type="text"placeholder="name-here" style="width:82%;">
              </div>
              <div class="col-md-12 text-right mt-4 pb-3 all-button ">
              <button type="button" class="btn btn-primary">Save Record</button>
<button type="button" class="btn btn-danger">Delete Record</button>
<button type="button" class="btn btn-success mr-4">Close Form</button>
              </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
  </div>
  </div>
  <div id="menu2" class="tab-pane fade card p-5">
  <div class="sales-group">
       <div class="row">
       <div class="col-md-2"></div>
       <div class="col-md-8">
           <div class="group-sale card">
               <h3 class="text-center">sales Group</h3>
               <div class="row text-center">
               <div class="col-md-4">
                   <p>Code</p>
               </div>
               <div class="col-md-8">
                   <input type="text" style="width:100%">
               </div>
               <div class="col-md-4">
                   <p>Name</p>
               </div>
               <div class="col-md-8">
                   <input type="text"style="width:100%">
               </div>
               <div class="col-md-4">
                   <p>Filter</p>
               </div>
               <div class="col-md-8">
               <select name="cars" class="custom-select"style="width:79%">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select><button type="button" class="btn btn-danger ml-2 fillter">Search</button>
               </div>
               </div>
           </div>

       </div>
       <div class="col-md-2"></div>
        </div>
    </div>
  </div>


  <div id="menu3" class="tab-pane fade card  p-5">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="date-enter card">
             <p>enter the latest date commisions were paid ether manually or by an automatted systeam Date needs to be  enter in mm/dd/yyyy.</p>
             <form action="/action_page.php">
  <label for="birthday">Latest paid commisions date</label>
  <input type="date" id="birthday" name="birthday">
  <input type="submit" value="Submit">
</form>
<input type="text"placeholder="Awaiting Your Input"class="mt-5 mb-5">
<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-6">
<button type="button" class="btn btn-primary">Load quick Book Data</button>
<button type="button" class="btn btn-danger">Cancle</button>
</div>

</div>

</div>
            </div>


        <div class="col-md-2"></div>
        </div>
    </div>




  <div id="menu4" class="tab-pane fade card  p-5">
  <h3 class="text-center mb-5">Commission Options</h3>
 <div class="row">

    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">commission method</label>
          </div>
          <div class="col-md-8">
          <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">

    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Range based</label>
          </div>
          <div class="col-md-8">
    <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">

    </div>
    <div class="col-md-10 d-flex value-here">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-7">
    <label for="email">Keep zero value iteam</label>
          </div>
          <div class="col-md-5">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-7">
    <label for="email">Zero Quantity items calulate as</label>
          </div>
          <div class="col-md-5">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-2">

    </div>
    <div class="col-md-10 d-flex">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-7">
    <label for="email">Uses Sales order Projections</label>
          </div>
          <div class="col-md-5">
          <input class="form-check-input ml-3" type="checkbox">
          </div>
      </div>
  </div>
</form>
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
      <div class="col-md-7">
    <label for="email">std cost is % of price</label>
          </div>
          <div class="col-md-5">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
    </div>

    <div class="col-md-2">

    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Rep manger over ride method </label>
          </div>
          <div class="col-md-8">
          <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Default rap comm%</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">use zip code Resps</label>
          </div>
          <div class="col-md-8">
    <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
    <form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Zip for Reports</label>
          </div>
          <div class="col-md-8">
    <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
    <form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">By pass method</label>
          </div>
          <div class="col-md-8">
    <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
    <form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Quote work Prefix</label>
          </div>
          <div class="col-md-8">
    <input type="text" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Inventory Valuation method</label>
          </div>
          <div class="col-md-8">
          <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
    <form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Accept Zero $ invoices</label>
          </div>
          <div class="col-md-8">
    <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Add cost discription</label>
          </div>
          <div class="col-md-8">
    <input type="email" class="form-control" placeholder="" id="">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Use Standard Cost as It</label>
          </div>
          <div class="col-md-8">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6"></div>
    <div class="col-md-6">
<form action="/action_page.php">
<div class="form-group">
      <div class="row">
          <div class="col-md-6">
    <label for="email">Check to pay full amount,unchecked to pay discounted amount</label>
          </div>
          <div class="col-md-6">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
    <form action="/action_page.php">
<div class="form-group">
      <div class="row">
          <div class="col-md-6">
    <label for="email">Cost Markup %</label>
          </div>
          <div class="col-md-6">
          <input type="text">
          </div>
          <div class="col-md-6">
    <label for="email">Line Cost Markup Amount</label>
          </div>
          <div class="col-md-6">
          <input type="text">
          </div><div class="col-md-6">
    <label for="email">Invoice cost Markup Amount </label>
          </div>
          <div class="col-md-6">
          <input type="text">
          </div>

      </div>
  </div>
</form>
    </div>
    <div class="col-md-6">
    <form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Invoices to select For Payment</label>
          </div>
          <div class="col-md-8">
          <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
</div>
<div class="col-md-6">
</div>
<div class="col-md-6">
<form action="/action_page.php">
<div class="form-group">
      <div class="row">
          <div class="col-md-6">
    <label for="email">Group Bill by Mg</label>
          </div>
          <div class="col-md-6">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
</div>
<div class="col-md-6">
</div>
<div class="col-md-6">
<form action="/action_page.php">
  <div class="form-group">
      <div class="row">
          <div class="col-md-4">
    <label for="email">Rep for Unsigned invoices</label>
          </div>
          <div class="col-md-8">
          <select name="cars" class="custom-select">
    <option selected></option>
    <option value="volvo">one</option>
    <option value="fiat">two</option>
    <option value="audi">three</option>
  </select>
          </div>
      </div>
  </div>
</form>
</div>
<div class="col-md-6">
<form action="/action_page.php">
<div class="form-group">
      <div class="row">
          <div class="col-md-6">
    <label for="email">Std cost is percent</label>
          </div>
          <div class="col-md-6">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
</div>
<div class="col-md-6">
<form action="/action_page.php">
<div class="form-group">
      <div class="row">
          <div class="col-md-6">
    <label for="email">Use Sales Rep Iteams Rate? </label>
          </div>
          <div class="col-md-6">
          <input class="form-check-input" type="checkbox">
          </div>
      </div>
  </div>
</form>
</div>
<div class="col-md-6">
</div>

<div class="col-md-6">
<form action="/action_page.php">
<div class="form-group">
      <div class="row">
          <div class="col-md-6">
    <label for="email">std cost custom field Name  </label>
          </div>
          <div class="col-md-6">
          <input type="text">
          </div>
      </div>
  </div>
</form>
</div>
<div class="col-md-6 d-flex" style="position: relative;">
<div class=zip-code>
Zip code To determine Rep:
</div>
<div class="form-check ml-2">
<input type="radio" class="form-check-input" name="optradio">Ship To
</div>

<div class="form-check ml-3">
<input type="radio" class="form-check-input" name="optradio">Bill To
</div>
</div>

<div class="col-md-6">
</div>


    <div class="col-md-6 text-right">
        <form method="POST" action="{{ route('system.getSalesrep') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Load Sales Reps</button>
        </form>


<button type="button" class="btn btn-danger">Close Form</button>

    </div>
</div>
        </div>
        </div>
    </div>
</div>


@endsection
