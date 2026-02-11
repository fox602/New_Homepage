using System.ComponentModel.DataAnnotations;

namespace New_Homepage.Models;

public class LoginViewModel
{
    [Required(ErrorMessage = "請輸入電子郵件")]
    [EmailAddress(ErrorMessage = "請輸入有效的電子郵件地址")]
    public string Email { get; set; } = string.Empty;

    [Required(ErrorMessage = "請輸入密碼")]
    [DataType(DataType.Password)]
    public string Password { get; set; } = string.Empty;

    public bool RememberMe { get; set; }
}
