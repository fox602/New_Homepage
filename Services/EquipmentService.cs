using Microsoft.EntityFrameworkCore;
using New_Homepage.Data;
using New_Homepage.Models;

namespace New_Homepage.Services;

public class EquipmentService : IEquipmentService
{
    private readonly ApplicationDbContext _context;

    public EquipmentService(ApplicationDbContext context)
    {
        _context = context;
    }

    public async Task<List<Equipment>> GetAllEquipmentsAsync()
    {
        return await _context.Equipments
            .OrderBy(e => e.EquipmentId)
            .ToListAsync();
    }

    public async Task<List<Equipment>> GetEquipmentsByCategoryAsync(string category)
    {
        return await _context.Equipments
            .Where(e => e.Category == category)
            .OrderBy(e => e.EquipmentId)
            .ToListAsync();
    }

    public async Task<Equipment?> GetEquipmentByIdAsync(string equipmentId)
    {
        return await _context.Equipments
            .FirstOrDefaultAsync(e => e.EquipmentId == equipmentId);
    }

    public async Task<List<WipData>> GetWipTrendDataAsync(int days = 7)
    {
        var startDate = DateTime.Today.AddDays(-days + 1);
        return await _context.WipData
            .Where(w => w.Date >= startDate)
            .OrderBy(w => w.Date)
            .ToListAsync();
    }
}
