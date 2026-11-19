$u = App\Models\User::where("email","adviser@spamast.edu.ph")->first(); $u->password = "Adviser@12345"; $u->save(); echo "ok";
