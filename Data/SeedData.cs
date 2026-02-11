using Microsoft.AspNetCore.Identity;
using Microsoft.EntityFrameworkCore;
using New_Homepage.Models;

namespace New_Homepage.Data;

public static class SeedData
{
    public static async Task InitializeAsync(IServiceProvider serviceProvider, IConfiguration configuration)
    {
        using var context = new ApplicationDbContext(
            serviceProvider.GetRequiredService<DbContextOptions<ApplicationDbContext>>());

        var userManager = serviceProvider.GetRequiredService<UserManager<IdentityUser>>();

        // 確保資料庫已建立
        await context.Database.EnsureCreatedAsync();

        // 建立預設管理員帳號
        var adminEmail = configuration["AppSettings:DefaultAdminEmail"] ?? "admin@semiconductor.com";
        var adminPassword = configuration["AppSettings:DefaultAdminPassword"] ?? "Admin@123";

        if (await userManager.FindByEmailAsync(adminEmail) == null)
        {
            var adminUser = new IdentityUser
            {
                UserName = adminEmail,
                Email = adminEmail,
                EmailConfirmed = true
            };

            var result = await userManager.CreateAsync(adminUser, adminPassword);
            if (!result.Succeeded)
            {
                throw new Exception($"Failed to create admin user: {string.Join(", ", result.Errors.Select(e => e.Description))}");
            }
        }

        // 新增機台資料
        if (!context.Equipments.Any())
        {
            var equipments = new List<Equipment>
            {
                new Equipment
                {
                    EquipmentId = "ET1-EQ001",
                    EquipmentName = "蝕刻機 #1",
                    Status = EquipmentStatus.Online,
                    Utilization = 95m,
                    Temperature = 85m,
                    Pressure = 5.2m,
                    Vacuum = 1.5m,
                    Capacity = 120,
                    LastUpdate = DateTime.Now,
                    Category = "ET1"
                },
                new Equipment
                {
                    EquipmentId = "ET1-EQ002",
                    EquipmentName = "蝕刻機 #2",
                    Status = EquipmentStatus.Online,
                    Utilization = 88m,
                    Temperature = 82m,
                    Pressure = 5.1m,
                    Vacuum = 1.4m,
                    Capacity = 115,
                    LastUpdate = DateTime.Now,
                    Category = "ET1"
                },
                new Equipment
                {
                    EquipmentId = "ET2-EQ001",
                    EquipmentName = "沉積機 #1",
                    Status = EquipmentStatus.Warning,
                    Utilization = 76m,
                    Temperature = 90m,
                    Pressure = 5.8m,
                    Vacuum = 1.8m,
                    Capacity = 98,
                    LastUpdate = DateTime.Now,
                    Category = "ET2"
                },
                new Equipment
                {
                    EquipmentId = "ET2-EQ002",
                    EquipmentName = "沉積機 #2",
                    Status = EquipmentStatus.Online,
                    Utilization = 91m,
                    Temperature = 84m,
                    Pressure = 5.3m,
                    Vacuum = 1.5m,
                    Capacity = 118,
                    LastUpdate = DateTime.Now,
                    Category = "ET2"
                },
                new Equipment
                {
                    EquipmentId = "ET3-001",
                    EquipmentName = "檢測設備 #1",
                    Status = EquipmentStatus.Offline,
                    Utilization = 0m,
                    Temperature = 25m,
                    Pressure = 0m,
                    Vacuum = 0m,
                    Capacity = 0,
                    LastUpdate = DateTime.Now.AddHours(-2),
                    Category = "ET3"
                },
                new Equipment
                {
                    EquipmentId = "ET3-002",
                    EquipmentName = "檢測設備 #2",
                    Status = EquipmentStatus.Online,
                    Utilization = 85m,
                    Temperature = 28m,
                    Pressure = 0m,
                    Vacuum = 0m,
                    Capacity = 95,
                    LastUpdate = DateTime.Now,
                    Category = "ET3"
                },
                new Equipment
                {
                    EquipmentId = "ET1-EQ003",
                    EquipmentName = "蝕刻機 #3",
                    Status = EquipmentStatus.Online,
                    Utilization = 92m,
                    Temperature = 86m,
                    Pressure = 5.4m,
                    Vacuum = 1.6m,
                    Capacity = 122,
                    LastUpdate = DateTime.Now,
                    Category = "ET1"
                },
                new Equipment
                {
                    EquipmentId = "ET2-EQ003",
                    EquipmentName = "沉積機 #3",
                    Status = EquipmentStatus.Warning,
                    Utilization = 72m,
                    Temperature = 92m,
                    Pressure = 6.1m,
                    Vacuum = 1.9m,
                    Capacity = 89,
                    LastUpdate = DateTime.Now,
                    Category = "ET2"
                }
            };

            await context.Equipments.AddRangeAsync(equipments);
            await context.SaveChangesAsync();
        }

        // 新增 WIP 資料
        if (!context.WipData.Any())
        {
            var wipData = new List<WipData>
            {
                new WipData { Date = DateTime.Today.AddDays(-6), WipCount = 1250 },
                new WipData { Date = DateTime.Today.AddDays(-5), WipCount = 1320 },
                new WipData { Date = DateTime.Today.AddDays(-4), WipCount = 1180 },
                new WipData { Date = DateTime.Today.AddDays(-3), WipCount = 1400 },
                new WipData { Date = DateTime.Today.AddDays(-2), WipCount = 1350 },
                new WipData { Date = DateTime.Today.AddDays(-1), WipCount = 1420 },
                new WipData { Date = DateTime.Today, WipCount = 1380 }
            };

            await context.WipData.AddRangeAsync(wipData);
            await context.SaveChangesAsync();
        }
    }
}
