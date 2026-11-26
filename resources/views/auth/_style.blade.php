<style>
    * { box-sizing: border-box; }
    body { margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: Arial, sans-serif; background: linear-gradient(135deg, #1e3a5f, #2d5a8e); color: #1f2937; padding: 20px; }
    .auth-card { background: #fff; width: 100%; max-width: 420px; padding: 32px; border-radius: 10px; box-shadow: 0 10px 30px rgba(0,0,0,.25); }
    .auth-brand { text-align: center; color: #1e3a5f; font-size: 22px; font-weight: bold; margin: 0 0 4px; }
    .auth-sub { text-align: center; color: #6b7280; margin: 0 0 22px; font-size: 14px; }
    .auth-card h2 { margin: 0 0 16px; font-size: 20px; color: #1e3a5f; }
    .field { margin-bottom: 14px; }
    .field label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: bold; }
    .field input[type=text], .field input[type=email], .field input[type=password] { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 15px; }
    .field input:focus { outline: none; border-color: #1e3a5f; box-shadow: 0 0 0 3px rgba(30,58,95,.15); }
    .remember { display: flex; align-items: center; gap: 6px; font-size: 14px; margin-bottom: 16px; }
    .btn { width: 100%; padding: 11px; background: #1e3a5f; color: #fff; border: 0; border-radius: 6px; font-size: 16px; font-weight: bold; cursor: pointer; }
    .btn:hover { background: #16304f; }
    .auth-link { text-align: center; margin: 18px 0 0; font-size: 14px; }
    .auth-link a { color: #1e3a5f; font-weight: bold; }
    .msg-ok { background: #dcfce7; color: #166534; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; font-size: 14px; }
    .msg-err { background: #fee2e2; color: #991b1b; padding: 10px 12px; border-radius: 6px; margin-bottom: 14px; font-size: 14px; }
    .msg-err ul { margin: 0; padding-left: 18px; }
</style>
